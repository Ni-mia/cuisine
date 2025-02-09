<?php

namespace App\Controller\API;

use App\Entity\Plat;
use App\Repository\PlatRepository;
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
use App\Repository\LiaisonPlatIngredientsRepository;
use App\Repository\PrixRepository;

class PlatApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/plats", methods: "GET")]
    // #[TokenRequired]
    function list(PlatRepository $repository){
        $platlist = $repository->findAll();
        return $this->json($platlist,200,[],[
            'groups' => ['plats.list']
        ]);
    }

    #[Route("/api/platsActif", methods: "GET")]
    function listActif(PlatRepository $repository){
        $platlist = $repository->findBy(['deletedAt' => null]);

        return $this->json($platlist, 200, [], [
            'groups' => ['plats.list']
        ]);
    }


    #[Route("/api/plat/{id}", methods: "GET")]
    function detail(PlatRepository $repository,int $id){
        $plat = $repository->findById($id);
        return $this->json($plat,200,[],[
            'groups' => ['plats.list']
        ]);
    }

    #[Route("/api/plat", methods: "POST")]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        RestaurantRepository $restaurantRepository
    ): Response {
        $data = json_decode($request->getContent(), true);
    
        if (!isset($data['id_restaurant_id'])) {
            return $this->json(['error' => 'id_restaurant_id est requis.'], 400);
        }
    
        $restaurant = $restaurantRepository->find($data['id_restaurant_id']);
        if (!$restaurant) {
            return $this->json(['error' => 'Restaurant non trouvé.'], 404);
        }
    
        $plat = $serializer->deserialize(
            $request->getContent(),
            Plat::class,
            'json',
            [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['idRestaurant'],
                AbstractNormalizer::OBJECT_TO_POPULATE => new Plat(),
            ]
        );
    
        // Associer le restaurant à l'objet Plat
        $plat->setIdRestaurant($restaurant);
    
        // Persister et sauvegarder dans la base de données
        $em->persist($plat);
        $em->flush();
    
        // Retourner une réponse avec l'objet créé
        return $this->json($plat, 201, [], [
            'groups' => ['plats.show']
        ]);
    }
    

    #[Route("/api/plat/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        PlatRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $plat = $repository->find($id);
        if (!$plat) {
            throw new NotFoundHttpException('Plat non trouvé');
        }

        $updatedPlat = $serializer->deserialize(
            $request->getContent(),
            Plat::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $plat,  'groups' => ['plats.update']]
        );

        $em->persist($updatedPlat);
        $em->flush();
        return $this->json($updatedPlat, 200, [], [
            'groups' => ['plats.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/plat/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        PlatRepository $repository,
    ) {
        $plat = $repository->find($id);
        if (!$plat) {
            throw new NotFoundHttpException('Plats non trouvé');
        }

        $deleteService->softDelete($plat);

        return new Response(null, 204);
    }

    #[Route("/api/plats/{id}/ingredients", methods: "GET")]
    function getIngredientsByPlat(int $id, LiaisonPlatIngredientsRepository $repository)
    {
        $liaisons = $repository->findBy(['idPlat' => $id]);

        if (!$liaisons) {
            return $this->json(['error' => 'Aucune liaison trouvée pour ce plat'], 404);
        }

        $ingredients = array_map(fn($liaison) => $liaison->getIdIngredients()->getId(), $liaisons);

        return $this->json($ingredients, 200);
    }

    #[Route("/api/plat/{id}/prix", methods: ["GET"])]
    function getPrix(int $id, PlatRepository $platRepo, PrixRepository $prixRepo)
    {
        $plat = $platRepo->find($id);
        
        if (!$plat) {
            return $this->json(['error' => 'Plat non trouvé'], 404);
        }

        $today = new \DateTime();

        // Chercher le prix actif
        $prixActuel = $prixRepo->createQueryBuilder('p')
            ->where('p.idPlat = :plat')
            ->andWhere(':today BETWEEN p.date_debut AND p.date_fin')
            ->setParameter('plat', $plat)
            ->setParameter('today', $today)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($prixActuel) {
            return $this->json([
                'idPlat' => $plat->getId(),
                'montant' => $prixActuel->getMontant(),
            ], 200);
        }

        // Si aucun prix actif, récupérer le dernier prix inséré
        $dernierPrix = $prixRepo->findOneBy(
            ['idPlat' => $plat],
            ['id' => 'DESC']
        );

        if ($dernierPrix) {
            return $this->json([
                'idPlat' => $plat->getId(),
                'montant' => $dernierPrix->getMontant(),
                'message' => 'deprecated price'
            ], 200);
        }

        return $this->json(['error' => 'Aucun prix disponible pour ce plat'], 404);
    }

}
