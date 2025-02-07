<?php

namespace App\Controller\API;

use App\Entity\Stock;
use App\Repository\StockRepository;
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
use App\Repository\IngredientsRepository;
use App\Repository\TypeMvtRepository;

class StockApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/stock", methods: "GET")]
    // #[TokenRequired]
    function list(StockRepository $repository){
        $stocklist = $repository->findAll();
        return $this->json($stocklist,200,[],[
            'groups' => ['stock.list']
        ]);
    }

    #[Route("/api/stock/{id}", methods: "GET")]
    function detail(StockRepository $repository,int $id){
        $stock = $repository->findById($id);
        return $this->json($stock,200,[],[
            'groups' => ['stock.list']
        ]);
    }

    #[Route("/api/stock", methods: ["POST"])]
    function create(
        Request $request,
        EntityManagerInterface $em,
        RestaurantRepository $restaurantRepo,
        IngredientsRepository $ingredientsRepo,
        TypeMvtRepository $typeMvtRepo
    ) {
        $data = json_decode($request->getContent(), true);
    
        // Vérifier que les données nécessaires sont présentes
        if (!isset($data['idRestaurant'], $data['idIngredient'], $data['quantite'])) {
            return $this->json(['error' => 'Données incomplètes'], 400);
        }
    
        // Récupérer les entités associées
        $restaurant = $restaurantRepo->find($data['idRestaurant']);
        $ingredient = $ingredientsRepo->find($data['idIngredient']);
        $typeMvt = isset($data['idType']) ? $typeMvtRepo->find($data['idType']) : null;
    
        if (!$restaurant || !$ingredient) {
            return $this->json(['error' => 'Restaurant ou Ingrédient non trouvé'], 404);
        }
    
        // Créer le stock
        $stock = new Stock();
        $stock->setIdRestaurant($restaurant);
        $stock->setIdIngredient($ingredient);
        $stock->setQuantite($data['quantite']);
        $stock->setDt(new \DateTime());
        if ($typeMvt) {
            $stock->setIdType($typeMvt);
        }
    
        $em->persist($stock);
        $em->flush();
    
        return $this->json($stock, 201, [], [
            'groups' => ['stock.show']
        ]);
    }
    
    

    #[Route("/api/stock/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        StockRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $stock = $repository->find($id);
        if (!$stock) {
            throw new NotFoundHttpException('Stock non trouvé');
        }

        $updatedStock = $serializer->deserialize(
            $request->getContent(),
            Stock::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $stock,  'groups' => ['stock.update']]
        );

        $em->persist($updatedStock);
        $em->flush();
        return $this->json($updatedStock, 200, [], [
            'groups' => ['stock.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/stock/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        StockRepository $repository,
    ) {
        $stock = $repository->find($id);
        if (!$stock) {
            throw new NotFoundHttpException('Stock non trouvé');
        }

        $deleteService->hardDelete($stock);

        return new Response(null, 204);
    }
}
