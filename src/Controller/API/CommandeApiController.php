<?php

namespace App\Controller\API;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
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
use App\Entity\Restaurant;
use App\Entity\Utilisateur;

class CommandeApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/commande", methods: "GET")]
    // #[TokenRequired]
    function list(CommandeRepository $repository){
        $commandelist = $repository->findAll();
        return $this->json($commandelist,200,[],[
            'groups' => ['commande.list']
        ]);
    }

    #[Route("/api/commande/{id}", methods: "GET")]
    function detail(CommandeRepository $repository,int $id){
        $commande = $repository->findById($id);
        return $this->json($commande,200,[],[
            'groups' => ['commande.list']
        ]);
    }

    #[Route("/api/commande", methods: "POST")]
    function create(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $utilisateur = $em->getRepository(Utilisateur::class)->find($data['idUtilisateur']);
        $restauarnt = $em->getRepository(Restaurant::class)->find($data['idRestaurant']);

        if (!$utilisateur || !$restauarnt) {
            return $this->json(['error' => 'Utilisateur ou Ingrédient non trouvé'], 404);
        }

        $commande = new Commande();
        $commande->setIdUtilisateur($utilisateur);
        $commande->setIdRestaurant($restauarnt);
        $commande->setDt($data['dt']);

        $em->persist($commande);
        $em->flush();

        return $this->json($commande, 200, [], [
            'groups' => ['commande.create']
        ]);
    }
    

    #[Route("/api/commande/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        CommandeRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $commande = $repository->find($id);
        if (!$commande) {
            throw new NotFoundHttpException('Commande non trouvé');
        }

        $updatedCommande = $serializer->deserialize(
            $request->getContent(),
            Commande::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $commande,  'groups' => ['commande.update']]
        );

        $em->persist($updatedCommande);
        $em->flush();
        return $this->json($updatedCommande, 200, [], [
            'groups' => ['commande.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/commande/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        CommandeRepository $repository,
    ) {
        $commande = $repository->find($id);
        if (!$commande) {
            throw new NotFoundHttpException('Commande non trouvé');
        }

        $deleteService->hardDelete($commande);

        return new Response(null, 204);
    }
}
