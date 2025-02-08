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

class UtilisateurApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/utilisateur", methods: "GET")]
    // #[TokenRequired]
    function list(UtilisateurRepository $repository){
        $utilisateurlist = $repository->findAll();
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

        // Vérifier si l'email existe
        $utilisateur = $em->getRepository(Utilisateur::class)->findOneBy(['mail' => $data['mail']]);
        if (!$utilisateur) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        // Vérifier le mot de passe (sans cryptage pour l'instant)
        if ($utilisateur->getMdp() !== $data['mdp']) {
            return $this->json(['error' => 'Mot de passe incorrect'], 401);
        }

        // Si tout est bon, on peut répondre avec un message simple (sans token pour l'instant)
        return $this->json(['message' => 'Connexion réussie']);
    }

    #[Route("/api/signIn", methods: "POST")]
    public function signIn(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        // Vérifier si l'email existe déjà
        $existingUser = $em->getRepository(Utilisateur::class)->findOneBy(['mail' => $data['mail']]);
        if ($existingUser) {
            return $this->json(['error' => 'Email déjà utilisé'], 400);
        }

        // Créer un nouvel utilisateur
        $utilisateur = new Utilisateur();
        $utilisateur->setNom($data['nom']);
        $utilisateur->setNomUtilisateur($data['nomUtilisateur']);
        $utilisateur->setMdp($data['mdp']); // Utilisation du mot de passe en clair (pas de hachage pour l'instant)
        $utilisateur->setMail($data['mail']);

        // Sauvegarder l'utilisateur dans la base de données
        $em->persist($utilisateur);
        $em->flush();

        // Répondre avec l'utilisateur créé
        return $this->json($utilisateur, 200, [], [
            'groups' => ['utilisateur.create']
        ]);
    }


}
