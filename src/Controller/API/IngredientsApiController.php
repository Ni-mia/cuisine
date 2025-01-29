<?php

namespace App\Controller\API;

use App\Entity\Ingredients;
use App\Repository\IngredientsRepository;
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


class IngredientsApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/ingredients", methods: "GET")]
    // #[TokenRequired]
    function list(IngredientsRepository $repository){
        $ingredientslist = $repository->findAll();
        return $this->json($ingredientslist,200,[],[
            'groups' => ['ingredients.list']
        ]);
    }

    #[Route("/api/ingredient/{id}", methods: "GET")]
    function detail(IngredientsRepository $repository,int $id){
        $ingredients = $repository->findById($id);
        return $this->json($ingredients,200,[],[
            'groups' => ['ingredients.list']
        ]);
    }

    #[Route("/api/ingredient", methods: "POST")]
    function create(
        #[MapRequestPayload(serializationContext: [
        'groups' => ['ingredients.create']
        ])] Ingredients $ingredient,
        EntityManagerInterface $em){
        $em->persist($ingredient);
        $em->flush();
        return $this->json($ingredient, 200, [], [
            'groups' => ['ingredients.show']
        ]);
    }
    

    #[Route("/api/ingredient/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        IngredientsRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $ingredients = $repository->find($id);
        if (!$ingredients) {
            throw new NotFoundHttpException('Ingredients non trouvé');
        }

        $updatedIngredients = $serializer->deserialize(
            $request->getContent(),
            Ingredients::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $ingredients,  'groups' => ['ingredients.update']]
        );

        $em->persist($updatedIngredients);
        $em->flush();
        return $this->json($updatedIngredients, 200, [], [
            'groups' => ['ingredients.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/ingredient/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        IngredientsRepository $repository,
    ) {
        $ingredients = $repository->find($id);
        if (!$ingredients) {
            throw new NotFoundHttpException('Ingredients non trouvé');
        }

        $deleteService->hardDelete($ingredients);

        return new Response(null, 204);
    }
}
