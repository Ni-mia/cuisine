<?php

namespace App\Controller\API;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Service\DeleteService;
use Symfony\Component\HttpFoundation\Response;
use App\Annotation\TokenRequired;
use App\Entity\Commande;
use App\Entity\Paiement;
use App\Entity\Role;
use App\Repository\CommandeRepository;
use App\Repository\PaiementRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class UtilisateurApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/utilisateur", methods: "GET")]
    // #[TokenRequired]
    function list(UtilisateurRepository $repository){
        $utilisateurlist = $repository->findBy(['idRole' => 1]);
        return $this->json($utilisateurlist,200,[],[
            'groups' => ['utilisateur.list']
        ]);
    }

    #[Route("/api/utilisateur/{id}", methods: "GET")]
    function detail(UtilisateurRepository $repository,int $id){
        $utilisateur = $repository->findById($id);
        return $this->json($utilisateur,200,[],[
            'groups' => ['utilisateur.list']
        ]);
    }

    #[Route("/api/utilisateur", methods: "POST")]
    function create(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $role = $em->getRepository(Role::class)->find($data['idRole']);

        if (!$role) {
            return $this->json(['error' => 'Role non trouvé'], 404);
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setIdRole($role);
        $utilisateur->setNom($data['nom']);
        $utilisateur->setMdp($data['mdp']);
        $utilisateur->setMail($data['mail']);
        $utilisateur->setNomUtilisateur($data['nomUtilisateur']);

        $em->persist($utilisateur);
        $em->flush();

        return $this->json($utilisateur, 200, [], [
            'groups' => ['utilisateur.create']
        ]);
    }
    

    #[Route("/api/utilisateur/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        UtilisateurRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $utilisateur = $repository->find($id);
        if (!$utilisateur) {
            throw new NotFoundHttpException('Utilisateur non trouvé');
        }

        $updatedUtilisateur = $serializer->deserialize(
            $request->getContent(),
            Utilisateur::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $utilisateur,  'groups' => ['utilisateur.update']]
        );

        $em->persist($updatedUtilisateur);
        $em->flush();
        return $this->json($updatedUtilisateur, 200, [], [
            'groups' => ['utilisateur.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/utilisateur/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        UtilisateurRepository $repository,
    ) {
        $utilisateur = $repository->find($id);
        if (!$utilisateur) {
            throw new NotFoundHttpException('Utilisateur non trouvé');
        }

        $deleteService->hardDelete($utilisateur);

        return new Response(null, 204);
    }
    //commande actu
    #[Route("/api/utilisateur/{id}/commandeActu", methods: ["GET"])]
    function getCommandeActu(
        int $id,
        UtilisateurRepository $userRepo,
        PaiementRepository $paiementRepo,
        EntityManagerInterface $em,
        CommandeRepository $commandeRepo,
        RestaurantRepository $restaurantRepo
    ) {
        $utilisateur = $userRepo->find($id);
        if (!$utilisateur) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        // 1. Trouver la dernière commande de l'utilisateur
        $commande = $commandeRepo->findOneBy(
            ['idUtilisateur' => $utilisateur],
            ['id' => 'DESC']
        );

        if ($commande) {
            $paiement = $paiementRepo->findOneBy(['idCommande' => $commande]);

            // 3. Si le paiement n'existe pas, on le crée
            if (!$paiement) {
                $paiement = new Paiement();
                $paiement->setIdCommande($commande);
                $paiement->setTotal(0);
                $paiement->setStatut(-1);
                $paiement->setDt(new \DateTime());
                $paiement->setDeletedAt(null);

                $em->persist($paiement);
                $em->flush();
            }

            // 4-5. Vérifier le statut du paiement
            if ($paiement->getStatut() === -1) {
                return $this->json(['idCommande' => $commande->getId()], 200);
            }
        }

        // 6. Si la dernière commande n'est plus active, on en crée une nouvelle
        $restaurant = $restaurantRepo->find(1); // ID du restaurant par défaut

        $nouvelleCommande = new Commande();
        $nouvelleCommande->setIdUtilisateur($utilisateur);
        $nouvelleCommande->setIdRestaurant($restaurant);
        $nouvelleCommande->setDt(new \DateTime());

        $em->persist($nouvelleCommande);
        $em->flush();

        // 7. Création du paiement pour la nouvelle commande
        $nouveauPaiement = new Paiement();
        $nouveauPaiement->setIdCommande($nouvelleCommande);
        $nouveauPaiement->setTotal(0);
        $nouveauPaiement->setStatut(-1);
        $nouveauPaiement->setDt(new \DateTime());
        $nouveauPaiement->setDeletedAt(null);

        $em->persist($nouveauPaiement);
        $em->flush();

        return $this->json(['idCommande' => $nouvelleCommande->getId()], 201);
    }

    #[Route("/api/login", methods: "POST")]
    public function login(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $utilisateur = $em->getRepository(Utilisateur::class)->findOneBy(['mail' => $data['mail']]);
        if (!$utilisateur) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        if ($utilisateur->getMdp() !== $data['mdp']) {
            return $this->json(['error' => 'Mot de passe incorrect'], 401);
        }

        return $this->json($utilisateur, 200, [], [
            'groups' => ['utilisateur.show']
        ]);
    }

    /*#[Route("/api/signIn", methods: "POST")]
    public function signIn(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $existingUser = $em->getRepository(Utilisateur::class)->findOneBy(['mail' => $data['mail']]);
        if ($existingUser) {
            return $this->json(['error' => 'Email déjà utilisé'], 400);
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setNom($data['nom']);
        $utilisateur->setNomUtilisateur($data['nomUtilisateur']);
        $utilisateur->setMdp($data['mdp']); 
        $utilisateur->setMail($data['mail']);
        $utilisateur->setIdRole($em->getRepository(Role::class)->find(1));

        $em->persist($utilisateur);
        $em->flush();

        return $this->json($utilisateur, 200, [], [
            'groups' => ['utilisateur.show']
        ]);
    }*/
    
    #[Route("/api/signIn", methods: "POST")]
    public function signIn(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['mail']) || !isset($data['mdp'])) {
            return $this->json(['error' => 'Email et mot de passe requis'], 400);
        }

        try {
            $firebaseUrl = "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=AIzaSyDXw-PqZrNfGI1oUBYCjKGE3DL81tRSSqQ";
            $postData = json_encode([
                "email" => $data['mail'],
                "password" => $data['mdp'],
                "returnSecureToken" => true
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $firebaseUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

            $response = curl_exec($ch);
            curl_close($ch);

            $firebaseData = json_decode($response, true);

            if (!isset($firebaseData['localId'])) {
                return $this->json(['error' => 'Création de compte Firebase échouée', 'details' => $firebaseData], 400);
            }

            $firebaseUid = $firebaseData['localId'];
            $email = $firebaseData['email'];

            $existingUser = $em->getRepository(Utilisateur::class)->findOneBy(['FirebaseId' => $firebaseUid]);

            if ($existingUser) {
                return $this->json(['message' => 'Utilisateur déjà inscrit'], 200);
            }

            // 🔹 Créer un nouvel utilisateur
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($data['nom']);
            $utilisateur->setNomUtilisateur($data['nomUtilisateur']);
            $utilisateur->setMdp($data['mdp']); 
            $utilisateur->setMail($email);
            $utilisateur->setFirebaseId($firebaseUid);
            $utilisateur->setIdRole($em->getRepository(Role::class)->find(1));

            $em->persist($utilisateur);
            $em->flush();

            return $this->json($utilisateur, 200, [], [
                'groups' => ['utilisateur.show']
            ]);
        } catch (\Throwable $e) {
            return $this->json(['error' => 'Erreur lors de l’authentification', 'message' => $e->getMessage()], 500);
        }
    }



    #[Route("/api/login/admin", methods: "POST")]
    public function loginAdmin(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        // Vérifier si l'utilisateur existe avec l'email donné
        $utilisateur = $em->getRepository(Utilisateur::class)->findOneBy(['mail' => $data['mail']]);
        if (!$utilisateur) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        // Vérifier si le mot de passe est correct
        if ($utilisateur->getMdp() !== $data['mdp']) {
            return $this->json(['error' => 'Mot de passe incorrect'], 401);
        }

        // Vérifier si l'utilisateur a le rôle d'admin (idRole == 2)
        if ($utilisateur->getIdRole()->getId() !== 2) {
            return $this->json(['error' => 'Accès réservé aux administrateurs'], 403);
        }

        // Si l'utilisateur est un admin, renvoyer les informations de l'utilisateur
        return $this->json($utilisateur, 200, [], [
            'groups' => ['utilisateur.show']
        ]);
    }


}
