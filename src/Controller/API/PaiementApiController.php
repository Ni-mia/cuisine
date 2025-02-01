<?php

namespace App\Controller\API;

use App\Entity\Paiement;
use App\Repository\PaiementRepository;
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


class PaiementApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/paiement", methods: "GET")]
    // #[TokenRequired]
    function list(PaiementRepository $repository){
        $paiementlist = $repository->findAll();
        return $this->json($paiementlist,200,[],[
            'groups' => ['paiement.list']
        ]);
    }

    #[Route("/api/paiement/{id}", methods: "GET")]
    function detail(PaiementRepository $repository,int $id){
        $paiement = $repository->findById($id);
        return $this->json($paiement,200,[],[
            'groups' => ['paiement.list']
        ]);
    }

    #[Route("/api/paiement", methods: "POST")]
    function create(
        #[MapRequestPayload(serializationContext: [
        'groups' => ['paiement.create']
        ])] Paiement $paiement,
        EntityManagerInterface $em){
        $em->persist($paiement);
        $em->flush();
        return $this->json($paiement, 200, [], [
            'groups' => ['paiement.show']
        ]);
    }
    

    #[Route("/api/paiement/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        PaiementRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $paiement = $repository->find($id);
        if (!$paiement) {
            throw new NotFoundHttpException('Paiement non trouvé');
        }

        $updatedPaiement = $serializer->deserialize(
            $request->getContent(),
            Paiement::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $paiement,  'groups' => ['paiement.update']]
        );

        $em->persist($updatedPaiement);
        $em->flush();
        return $this->json($updatedPaiement, 200, [], [
            'groups' => ['paiement.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/paiement/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        PaiementRepository $repository,
    ) {
        $paiement = $repository->find($id);
        if (!$paiement) {
            throw new NotFoundHttpException('Paiement non trouvé');
        }

        $deleteService->hardDelete($paiement);

        return new Response(null, 204);
    }
}
