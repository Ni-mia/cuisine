<?php

namespace App\Controller\API;

use App\Entity\TypeMvt;
use App\Repository\TypeMvtRepository;
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


class TypeMvtApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/typemvt", methods: "GET")]
    // #[TokenRequired]
    function list(TypeMvtRepository $repository){
        $typemvtlist = $repository->findAll();
        return $this->json($typemvtlist,200,[],[
            'groups' => ['typemvt.list']
        ]);
    }

    #[Route("/api/typemvt/{id}", methods: "GET")]
    function detail(TypeMvtRepository $repository,int $id){
        $typemvt = $repository->findById($id);
        return $this->json($typemvt,200,[],[
            'groups' => ['typemvt.list']
        ]);
    }

    #[Route("/api/typemvt", methods: "POST")]
    function create(
        #[MapRequestPayload(serializationContext: [
        'groups' => ['typemvt.create']
        ])] TypeMvt $typemvt,
        EntityManagerInterface $em){
        $em->persist($typemvt);
        $em->flush();
        return $this->json($typemvt, 200, [], [
            'groups' => ['typemvt.show']
        ]);
    }
    

    #[Route("/api/typemvt/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        TypeMvtRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $typemvt = $repository->find($id);
        if (!$typemvt) {
            throw new NotFoundHttpException('TypeMvt non trouvé');
        }

        $updatedTypeMvt = $serializer->deserialize(
            $request->getContent(),
            TypeMvt::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $typemvt,  'groups' => ['typemvt.update']]
        );

        $em->persist($updatedTypeMvt);
        $em->flush();
        return $this->json($updatedTypeMvt, 200, [], [
            'groups' => ['typemvt.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/typemvt/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        TypeMvtRepository $repository,
    ) {
        $typemvt = $repository->find($id);
        if (!$typemvt) {
            throw new NotFoundHttpException('TypeMvt non trouvé');
        }

        $deleteService->hardDelete($typemvt);

        return new Response(null, 204);
    }
}
