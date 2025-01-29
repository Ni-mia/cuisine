<?php

namespace App\Controller\API;

use App\Entity\Project;
use App\Repository\ProjectRepository;
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


class ProjectApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/projects", methods: "GET")]
    // #[TokenRequired]
    function list(ProjectRepository $repository){
        $projectlist = $repository->findAll();
        return $this->json($projectlist,200,[],[
            'groups' => ['projects.list']
        ]);
    }

    #[Route("/api/project/{id}", methods: "GET")]
    function detail(ProjectRepository $repository,int $id){
        $project = $repository->findById($id);
        return $this->json($project,200,[],[
            'groups' => ['projects.list']
        ]);
    }

    #[Route("/api/project", methods: "POST")]
    function create(
        #[MapRequestPayload(serializationContext: [
        'groups' => ['projects.create']
        ])] Project $project,
        EntityManagerInterface $em){
        $em->persist($project);
        $em->flush();
    }

    #[Route("/api/project/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        ProjectRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $project = $repository->find($id);
        if (!$project) {
            throw new NotFoundHttpException('Project non trouvé');
        }

        $updatedProject = $serializer->deserialize(
            $request->getContent(),
            Project::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $project,  'groups' => ['projects.update']]
        );

        $em->persist($updatedProject);
        $em->flush();
        return $this->json($updatedProject, 200, [], [
            'groups' => ['projects.show']
        ]);
    }

    #[Route("/api/project/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        ProjectRepository $repository,
    ) {
        $project = $repository->find($id);
        if (!$project) {
            throw new NotFoundHttpException('Projects non trouvé');
        }

        $deleteService->softDelete($project);

        return new Response(null, 204);
    }
}
