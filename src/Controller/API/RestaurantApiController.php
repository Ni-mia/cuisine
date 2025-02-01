<?php

namespace App\Controller\API;

use App\Entity\Restaurant;
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


class RestaurantApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/restaurant", methods: "GET")]
    // #[TokenRequired]
    function list(RestaurantRepository $repository){
        $restaurantlist = $repository->findAll();
        return $this->json($restaurantlist,200,[],[
            'groups' => ['restaurant.list']
        ]);
    }

    #[Route("/api/restaurant/{id}", methods: "GET")]
    function detail(RestaurantRepository $repository,int $id){
        $restaurant = $repository->findById($id);
        return $this->json($restaurant,200,[],[
            'groups' => ['restaurant.list']
        ]);
    }

    #[Route("/api/restaurant", methods: "POST")]
    function create(
        #[MapRequestPayload(serializationContext: [
        'groups' => ['restaurant.create']
        ])] Restaurant $restaurant,
        EntityManagerInterface $em){
        $em->persist($restaurant);
        $em->flush();
        return $this->json($restaurant, 200, [], [
            'groups' => ['restaurant.show']
        ]);
    }
    

    #[Route("/api/restaurant/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        RestaurantRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $restaurant = $repository->find($id);
        if (!$restaurant) {
            throw new NotFoundHttpException('Restaurant non trouvé');
        }

        $updatedRestaurant = $serializer->deserialize(
            $request->getContent(),
            Restaurant::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant,  'groups' => ['restaurant.update']]
        );

        $em->persist($updatedRestaurant);
        $em->flush();
        return $this->json($updatedRestaurant, 200, [], [
            'groups' => ['restaurant.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/restaurant/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        RestaurantRepository $repository,
    ) {
        $restaurant = $repository->find($id);
        if (!$restaurant) {
            throw new NotFoundHttpException('Restaurant non trouvé');
        }

        $deleteService->hardDelete($restaurant);

        return new Response(null, 204);
    }
}
