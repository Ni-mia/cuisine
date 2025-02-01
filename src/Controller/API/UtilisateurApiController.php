<?php

namespace App\Controller\API;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
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


class UtilisateurApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/utilisateur", methods: "GET")]
    // #[TokenRequired]
    function list(UtilisateurRepository $repository){
        $utilisateurlist = $repository->findAll();
        return $this->json($utilisateurlist,200,[],[
            'groups' => ['utilisateur.list']
        ]);
    }

    #[Route("/api/utilisateur/{id}", methods: "GET")]
    function detail(UtilisateurRepository $repository,int $id){
        $utilisateur = $repository->findById($id);
        return $this->json($utilisateur,200,[],[
            'groups' => ['utilisateur.list']
        ]);
    }

    #[Route("/api/utilisateur", methods: "POST")]
    function create(
        #[MapRequestPayload(serializationContext: [
        'groups' => ['utilisateur.create']
        ])] Utilisateur $utilisateur,
        EntityManagerInterface $em){
        $em->persist($utilisateur);
        $em->flush();
        return $this->json($utilisateur, 200, [], [
            'groups' => ['utilisateur.show']
        ]);
    }
    

    #[Route("/api/utilisateur/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        UtilisateurRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $utilisateur = $repository->find($id);
        if (!$utilisateur) {
            throw new NotFoundHttpException('Utilisateur non trouvé');
        }

        $updatedUtilisateur = $serializer->deserialize(
            $request->getContent(),
            Utilisateur::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $utilisateur,  'groups' => ['utilisateur.update']]
        );

        $em->persist($updatedUtilisateur);
        $em->flush();
        return $this->json($updatedUtilisateur, 200, [], [
            'groups' => ['utilisateur.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/utilisateur/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        UtilisateurRepository $repository,
    ) {
        $utilisateur = $repository->find($id);
        if (!$utilisateur) {
            throw new NotFoundHttpException('Utilisateur non trouvé');
        }

        $deleteService->hardDelete($utilisateur);

        return new Response(null, 204);
    }
}
