<?php

namespace App\Controller\API;

use App\Entity\Role;
use App\Repository\RoleRepository;
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


class RoleApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/role", methods: "GET")]
    // #[TokenRequired]
    function list(RoleRepository $repository){
        $rolelist = $repository->findAll();
        return $this->json($rolelist,200,[],[
            'groups' => ['role.list']
        ]);
    }

    #[Route("/api/role/{id}", methods: "GET")]
    function detail(RoleRepository $repository,int $id){
        $role = $repository->findById($id);
        return $this->json($role,200,[],[
            'groups' => ['role.list']
        ]);
    }

    #[Route("/api/role", methods: "POST")]
    function create(
        #[MapRequestPayload(serializationContext: [
        'groups' => ['role.create']
        ])] Role $role,
        EntityManagerInterface $em){
        $em->persist($role);
        $em->flush();
        return $this->json($role, 200, [], [
            'groups' => ['role.show']
        ]);
    }
    

    #[Route("/api/role/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        RoleRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $role = $repository->find($id);
        if (!$role) {
            throw new NotFoundHttpException('Role non trouvé');
        }

        $updatedRole = $serializer->deserialize(
            $request->getContent(),
            Role::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $role,  'groups' => ['role.update']]
        );

        $em->persist($updatedRole);
        $em->flush();
        return $this->json($updatedRole, 200, [], [
            'groups' => ['role.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/role/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        RoleRepository $repository,
    ) {
        $role = $repository->find($id);
        if (!$role) {
            throw new NotFoundHttpException('Role non trouvé');
        }

        $deleteService->hardDelete($role);

        return new Response(null, 204);
    }
}
