<?php

namespace App\Controller\API;

use App\Entity\DetailsCommande;
use App\Repository\DetailsCommandeRepository;
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
use App\Entity\Plat;
use App\Repository\CommandeRepository;
use App\Repository\PaiementRepository;
use App\Repository\PlatRepository;
use App\Repository\UtilisateurRepository;

class DetailsCommandeApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/detailsCommande", methods: "GET")]
    // #[TokenRequired]
    function list(DetailsCommandeRepository $repository){
        $detailsCommandelist = $repository->findAll();
        return $this->json($detailsCommandelist,200,[],[
            'groups' => ['detailsCommande.list']
        ]);
    }

    #[Route("/api/detailsCommande/{id}", methods: "GET")]
    function detail(DetailsCommandeRepository $repository,int $id){
        $detailsCommande = $repository->find($id);
        return $this->json($detailsCommande,200,[],[
            'groups' => ['detailsCommande.list']
        ]);
    }

    #[Route("/api/detailsCommande", methods: "POST")]
    function create(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $plat = $em->getRepository(Plat::class)->find($data['idPlat']);
        $commande = $em->getRepository(Commande::class)->find($data['idCommande']);

        if (!$plat || !$commande) {
            return $this->json(['error' => 'Plat ou Ingrédient non trouvé'], 404);
        }

        $detailsCommande = new DetailsCommande();
        $detailsCommande->setIdPlat($plat);
        $detailsCommande->setIdCommande($commande);
        $detailsCommande->setStatut($data['statut']);

        $em->persist($detailsCommande);
        $em->flush();

        return $this->json($detailsCommande, 200, [], [
            'groups' => ['detailsCommande.create']
        ]);
    }
    

    #[Route("/api/detailsCommande/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        DetailsCommandeRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $detailsCommande = $repository->find($id);
        if (!$detailsCommande) {
            throw new NotFoundHttpException('DetailsCommande non trouvé');
        }

        $updatedDetailsCommande = $serializer->deserialize(
            $request->getContent(),
            DetailsCommande::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $detailsCommande,  'groups' => ['detailsCommande.update']]
        );

        $em->persist($updatedDetailsCommande);
        $em->flush();
        return $this->json($updatedDetailsCommande, 200, [], [
            'groups' => ['detailsCommande.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/detailsCommande/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        DetailsCommandeRepository $repository,
    ) {
        $detailsCommande = $repository->find($id);
        if (!$detailsCommande) {
            throw new NotFoundHttpException('DetailsCommande non trouvé');
        }

        $deleteService->hardDelete($detailsCommande);

        return new Response(null, 204);
    }
    #[Route("/api/detailsCommande/multi", methods: ["POST"])]
    function createMulti(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $plat = $em->getRepository(Plat::class)->find($data['idPlat']);
        $commande = $em->getRepository(Commande::class)->find($data['idCommande']);

        if (!$plat || !$commande) {
            return $this->json(['error' => 'Plat ou Commande non trouvé'], 404);
        }

        if (!isset($data['quantite']) || $data['quantite'] <= 0) {
            return $this->json(['error' => 'Quantité invalide'], 400);
        }

        $detailsCommandeList = [];
        
        for ($i = 0; $i < $data['quantite']; $i++) {
            $detailsCommande = new DetailsCommande();
            $detailsCommande->setIdPlat($plat);
            $detailsCommande->setIdCommande($commande);
            $detailsCommande->setStatut(-1);
            $detailsCommande->setDeletedAt(null);

            $em->persist($detailsCommande);
            $detailsCommandeList[] = $detailsCommande;
        }

        $em->flush();

        return $this->json($detailsCommandeList, 201, [], [
            'groups' => ['detailsCommande.create']
        ]);
    }

    #[Route("/api/detailsCommande/countPlats", methods: "GET")]
    function countPlats(EntityManagerInterface $em)
    {
        $query = $em->createQuery(
            "SELECT p.id AS id, p.nom AS nomPlat, COUNT(d.id) AS commande
            FROM App\Entity\DetailsCommande d
            JOIN d.idPlat p
            GROUP BY p.id, p.nom"
        );

        $result = $query->getResult();

        return $this->json($result, 200);
    }


}
