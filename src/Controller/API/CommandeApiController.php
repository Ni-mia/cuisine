<?php

namespace App\Controller\API;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
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
use App\Entity\Paiement;
use App\Entity\Restaurant;
use App\Entity\Utilisateur;
use App\Repository\DetailsCommandeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommandeApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/commande", methods: "GET")]
    // #[TokenRequired]
    function list(CommandeRepository $repository){
        $commandelist = $repository->findAll();
        return $this->json($commandelist,200,[],[
            'groups' => ['commande.list']
        ]);
    }

    #[Route("/api/commande/{id}", methods: "GET")]
    function detail(CommandeRepository $repository,int $id){
        $commande = $repository->findById($id);
        return $this->json($commande,200,[],[
            'groups' => ['commande.list']
        ]);
    }

    #[Route("/api/commande", methods: ["POST"])]
    function create(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);
    
        $utilisateur = $em->getRepository(Utilisateur::class)->find($data['idUtilisateur']);
        $restaurant = $em->getRepository(Restaurant::class)->find($data['idRestaurant']);
    
        if (!$utilisateur || !$restaurant) {
            return $this->json(['error' => 'Utilisateur ou Restaurant non trouvé'], 404);
        }
    
        $commande = new Commande();
        $commande->setIdUtilisateur($utilisateur);
        $commande->setIdRestaurant($restaurant);
        $commande->setDt(new \DateTime($request->get('dt')));
    
        $em->persist($commande);
        $em->flush(); 
    
        $paiement = new Paiement();
        $paiement->setIdCommande($commande);
        $paiement->setTotal(0);
        $paiement->setStatut(-1);
        $paiement->setDeletedAt(null);
    
        $em->persist($paiement);
        $em->flush();
    
        return $this->json($commande, 200, [], [
            'groups' => ['commande.create']
        ]);
    }
    

    #[Route("/api/commande/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        CommandeRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $commande = $repository->find($id);
        if (!$commande) {
            throw new NotFoundHttpException('Commande non trouvé');
        }

        $updatedCommande = $serializer->deserialize(
            $request->getContent(),
            Commande::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $commande,  'groups' => ['commande.update']]
        );

        $em->persist($updatedCommande);
        $em->flush();
        return $this->json($updatedCommande, 200, [], [
            'groups' => ['commande.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/commande/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        CommandeRepository $repository,
    ) {
        $commande = $repository->find($id);
        if (!$commande) {
            throw new NotFoundHttpException('Commande non trouvé');
        }

        $deleteService->hardDelete($commande);

        return new Response(null, 204);
    }
    #[Route("/api/commande/{id}/detailsCommande", methods: ["GET"])]
    function getDetailsCommandeByCommande(int $id, DetailsCommandeRepository $repository)
    {
        $detailsCommande = $repository->findBy(['idCommande' => $id]);

        if (!$detailsCommande) {
            return $this->json(['error' => 'Aucun détail de commande trouvé pour cette commande'], 404);
        }

        $result = array_map(function ($detail) {
            return [
                'id' => $detail->getId(),
                'idCommande' => $detail->getIdCommande()->getId(),
                'idPlat' => $detail->getIdPlat()->getId(),
                'statut' => $detail->getStatut(),
                'deletedAt' => $detail->getDeletedAt()
            ];
        }, $detailsCommande);

        return $this->json($result, 200);
    }



    #[Route("/api/commandes/par-jour", methods: ["GET"])]
    public function commandeJour(CommandeRepository $repository): JsonResponse
    {
        $commandes = $repository->createQueryBuilder('c')
            ->select('c.dt')
            ->getQuery()
            ->getResult();

        $commandesParJour = [];
        foreach ($commandes as $commande) {
            $jour = $commande['dt']->format('Y-m-d');
            if (!isset($commandesParJour[$jour])) {
                $commandesParJour[$jour] = 0;
            }
            $commandesParJour[$jour]++;
        }

        return $this->json(array_map(function ($jour, $nombre) {
            return ['jour' => $jour, 'nombre' => $nombre];
        }, array_keys($commandesParJour), $commandesParJour), 200, [], [
            'groups' => ['commande.list']
        ]);
    }

    #[Route("/api/commande/simpleDetails", methods: ["GET"])]
    function getSimpleDetailsCommandeAll(DetailsCommandeRepository $detailsRepo, CommandeRepository $commandeRepo)
    {
        // 🔹 Récupérer toutes les commandes (triées du plus récent au plus ancien)
        $commandes = $commandeRepo->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC') // Tri par ID commande décroissant
            ->getQuery()
            ->getResult();

        // 🔹 Construire la réponse
        $result = array_map(function ($commande) use ($detailsRepo) {
            $utilisateur = $commande->getIdUtilisateur();
            $nomUtilisateur = $utilisateur ? $utilisateur->getNomUtilisateur() : 'Inconnu';

            // 🔹 Récupérer les détails de commande pour cette commande
            $detailsCommande = $detailsRepo->findBy(
                ['idCommande' => $commande->getId()],
                ['id' => 'DESC'] // Tri par ID décroissant
            );

            // 🔹 Extraire les ID des plats
            $idPlats = array_map(fn($detail) => $detail->getIdPlat()->getId(), $detailsCommande);

            return [
                'idCommande' => $commande->getId(), // ✅ Ajout de l'ID commande
                'nomUtilisateur' => $nomUtilisateur,
                'idPlats' => $idPlats
            ];
        }, $commandes);

        return $this->json($result, 200);
    }
    

}
