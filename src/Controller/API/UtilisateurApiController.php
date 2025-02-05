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
function getCommandeActu(int $id, UtilisateurRepository $userRepo, PaiementRepository $paiementRepo, EntityManagerInterface $em, CommandeRepository $commandeRepo, RestaurantRepository $restaurantRepo)
{
    // Trouver l'utilisateur
    $utilisateur = $userRepo->find($id);
    if (!$utilisateur) {
        return $this->json(['error' => 'Utilisateur non trouvé'], 404);
    }

    // Trouver la commande de l'utilisateur
    $commande = $commandeRepo->findOneBy(['idUtilisateur' => $id]);

    // Si aucune commande n'existe, créer une nouvelle commande
    if (!$commande) {
        $commande = new Commande();
        $commande->setIdUtilisateur($utilisateur);

        // Assigner un restaurant avec l'ID 1 par défaut
        $restaurant = $restaurantRepo->find(1); // Assigner l'idRestaurant avec 1
        if (!$restaurant) {
            return $this->json(['error' => 'Restaurant non trouvé'], 404);
        }
        $commande->setIdRestaurant($restaurant);

        // Assigner la date actuelle à la colonne dt
        $commande->setDt(new \DateTime());

        $em->persist($commande);
        $em->flush();

        // Créer un paiement associé à la nouvelle commande
        $paiement = new Paiement();
        $paiement->setIdCommande($commande);
        $paiement->setTotal(0);
        $paiement->setStatut(-1);
        $paiement->setDt(new \DateTime());
        $paiement->setDeletedAt(null);

        $em->persist($paiement);
        $em->flush();

        return $this->json([
            'idCommande' => $commande->getId(),
            'statutPaiement' => $paiement->getStatut()
        ], 201);
    }

    // Si une commande existe déjà, vérifier le paiement
    $paiement = $paiementRepo->findOneBy([
        'idCommande' => $commande,
        'statut' => -1
    ], ['id' => 'DESC']);

    // Si un paiement existe, renvoyer la commande actuelle
    if ($paiement) {
        return $this->json([
            'idCommande' => $paiement->getIdCommande()->getId(),
            'statutPaiement' => $paiement->getStatut()
        ], 200);
    }

    // Créer une nouvelle commande si nécessaire
    $commande = new Commande();
    $commande->setIdUtilisateur($utilisateur);

    // Assigner le restaurant par défaut
    $restaurant = $restaurantRepo->find(1); // Restaurant avec ID 1
    if (!$restaurant) {
        return $this->json(['error' => 'Restaurant non trouvé'], 404);
    }
    $commande->setIdRestaurant($restaurant);

    // Assigner la date actuelle à la colonne dt
    $commande->setDt(new \DateTime());

    $em->persist($commande);
    $em->flush();

    // Créer le paiement
    $paiement = new Paiement();
    $paiement->setIdCommande($commande);
    $paiement->setTotal(0);
    $paiement->setStatut(-1);
    $paiement->setDt(new \DateTime());
    $paiement->setDeletedAt(null);

    $em->persist($paiement);
    $em->flush();

    return $this->json([
        'idCommande' => $commande->getId(),
        'statutPaiement' => $paiement->getStatut()
    ], 201);
}

}
