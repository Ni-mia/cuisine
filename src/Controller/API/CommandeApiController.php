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
    public function getNombreCommandesParJour(CommandeRepository $commandeRepo): JsonResponse
    {
        $commandes = $commandeRepo->createQueryBuilder('c')
            ->select("DATE_FORMAT(c.dt, '%Y-%m-%d') as jour, COUNT(c.id) as nombre")
            ->groupBy('jour')
            ->orderBy('jour', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->json($commandes);
    }

}
