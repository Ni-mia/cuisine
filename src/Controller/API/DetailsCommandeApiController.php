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
use Symfony\Component\HttpFoundation\JsonResponse;

class DetailsCommandeApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/detailsCommande", methods: "GET")]
    // #[TokenRequired]
    function list(DetailsCommandeRepository $repository){
        $detailsCommandelist = $repository->findAll();
        return $this->json($detailsCommandelist,200,[],[
            'groups' => ['detailsCommande.list'],
        ]);
    }

    #[Route("/api/detailsCommande2", methods: "GET")]
    function list2(EntityManagerInterface $em)
    {
        $query = $em->createQuery(
            "SELECT d.id AS id, c.id AS commande, p.id AS plat, d.statut as statut, d. deletedAt as deletedAt 
            FROM App\Entity\DetailsCommande d
            JOIN d.idCommande c
            JOIN d.idPlat p"
        );

        $result = $query->getResult();

        return $this->json($result, 200);
    }


    #[Route("/api/detailsCommande/{id<\d+>}", methods: "GET")]
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
            "SELECT p.id AS id, p.nom AS nomPlat, COUNT(DISTINCT d.id) AS commande
            FROM App\Entity\DetailsCommande d
            JOIN d.idPlat p
            GROUP BY p.id, p.nom"
        );
        

        $result = $query->getResult();

        return $this->json($result, 200);
    }

    #[Route("/api/detailsCommande/{id}/cooking", methods: ["PUT"])]
    function preparerPlat(int $id, DetailsCommandeRepository $repository, EntityManagerInterface $em)
    {
        $detailsCommande = $repository->findOneBy(['id' => $id]);
        if (!$detailsCommande) {
            return $this->json(['error' => 'Aucun detailsCommande trouvé pour cet id'], 404);
        }
        $detailsCommande->setStatut(1);
        $em->persist($detailsCommande);
        $em->flush();

        return $this->json([
            'message' => 'Debut de la preparation du plat du detailsCommande',
            'statut' => $detailsCommande->getStatut()
        ], 200);
    }
    #[Route("/api/detailsCommande/{id}/livrer", methods: ["PUT"])]
    function livrerPlat(int $id, DetailsCommandeRepository $repository, EntityManagerInterface $em)
    {
        $detailsCommande = $repository->findOneBy(['id' => $id]);
        if (!$detailsCommande) {
            return $this->json(['error' => 'Aucun detailsCommande trouvé pour cet id'], 404);
        }
        $detailsCommande->setStatut(2);
        $em->persist($detailsCommande);
        $em->flush();

        return $this->json([
            'message' => 'Plat livrer avec succes',
            'statut' => $detailsCommande->getStatut()
        ], 200);
    }
    #[Route("/api/detailsCommande/{id}/recu", methods: ["PUT"])]
    function recuPlat(int $id, DetailsCommandeRepository $repository, EntityManagerInterface $em)
    {
        $detailsCommande = $repository->findOneBy(['id' => $id]);
        if (!$detailsCommande) {
            return $this->json(['error' => 'Aucun detailsCommande trouvé pour cet id'], 404);
        }
        $detailsCommande->setStatut(3);
        $em->persist($detailsCommande);
        $em->flush();

        return $this->json([
            'message' => 'Plat recu avec succes',
            'statut' => $detailsCommande->getStatut()
        ], 200);
    }

    #[Route("/api/commande/{id}/prepare", methods: ["POST"])]
public function prepareCommande(int $id, DetailsCommandeRepository $repository, EntityManagerInterface $em)
{
    // Récupérer tous les détails de la commande par l'ID
    $detailsCommande = $repository->findBy(['idCommande' => $id]);

    // Vérifier si des détails de commande ont été trouvés
    if (!$detailsCommande) {
        return $this->json(['error' => 'Aucun détail de commande trouvé pour cette commande'], 404);
    }

    // Mettre à jour le statut de chaque détail de commande à 0
    foreach ($detailsCommande as $detail) {
        $detail->setStatut(0);  // Assumer qu'il y a une méthode setStatut sur ton entité
    }

    // Persister les modifications dans la base de données
    $em->flush();

    return $this->json(['message' => 'La commande a été préparée, tous les statuts ont été mis à 0'], 200);
}

}
