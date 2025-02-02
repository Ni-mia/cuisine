<?php

namespace App\Controller\API;

use App\Entity\Prix;
use App\Repository\PrixRepository;
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
use App\Entity\Plat;

class PrixApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/prix", methods: "GET")]
    // #[TokenRequired]
    function list(PrixRepository $repository){
        $prixlist = $repository->findAll();
        return $this->json($prixlist,200,[],[
            'groups' => ['prix.list']
        ]);
    }

    #[Route("/api/prix/{id}", methods: "GET")]
    function detail(PrixRepository $repository,int $id){
        $prix = $repository->findById($id);
        return $this->json($prix,200,[],[
            'groups' => ['prix.list']
        ]);
    }

    #[Route("/api/prix", methods: "POST")]
    function create(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $plat = $em->getRepository(Plat::class)->find($data['idPlat']);

        if (!$plat) {
            return $this->json(['error' => 'Plat ou Ingrédient non trouvé'], 404);
        }

        $prix = new Prix();
        $prix->setIdPlat($plat);
        $prix->setMontant($data['montant']);
        $prix->setDateDebut(new \DateTime($request->get('date_debut')));
        $prix->setDateFin(new \DateTime($request->get('date_fin')));

        $em->persist($prix);
        $em->flush();

        return $this->json($prix, 200, [], [
            'groups' => ['prix.create']
        ]);
    }
    

    #[Route("/api/prix/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        PrixRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $prix = $repository->find($id);
        if (!$prix) {
            throw new NotFoundHttpException('Prix non trouvé');
        }

        $updatedPrix = $serializer->deserialize(
            $request->getContent(),
            Prix::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $prix,  'groups' => ['prix.update']]
        );

        $em->persist($updatedPrix);
        $em->flush();
        return $this->json($updatedPrix, 200, [], [
            'groups' => ['prix.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/prix/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        PrixRepository $repository,
    ) {
        $prix = $repository->find($id);
        if (!$prix) {
            throw new NotFoundHttpException('Prix non trouvé');
        }

        $deleteService->hardDelete($prix);

        return new Response(null, 204);
    }
}
