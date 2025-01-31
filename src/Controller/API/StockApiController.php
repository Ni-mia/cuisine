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

    #[Route("/api/stock", methods: "POST")]
    function create(
        #[MapRequestPayload(serializationContext: [
        'groups' => ['stock.create']
        ])] Stock $stock,
        EntityManagerInterface $em){
        $em->persist($stock);
        $em->flush();
        return $this->json($stock, 200, [], [
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
