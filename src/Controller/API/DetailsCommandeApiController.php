<?php

namespace App\Controller\API;

use App\Entity\DetailsCommande;
use App\Repository\DetailsCommandeRepository;
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
use App\Entity\Commande;
use App\Entity\Paiement;
use App\Entity\Plat;
use App\Repository\CommandeRepository;
use App\Repository\PaiementRepository;
use App\Repository\PlatRepository;
use App\Repository\UtilisateurRepository;

class DetailsCommandeApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/detailsCommande", methods: "GET")]
    // #[TokenRequired]
    function list(DetailsCommandeRepository $repository){
        $detailsCommandelist = $repository->findAll();
        return $this->json($detailsCommandelist,200,[],[
            'groups' => ['detailsCommande.list']
        ]);
    }

    #[Route("/api/detailsCommande/{id}", methods: "GET")]
    function detail(DetailsCommandeRepository $repository,int $id){
        $detailsCommande = $repository->findById($id);
        return $this->json($detailsCommande,200,[],[
            'groups' => ['detailsCommande.list']
        ]);
    }

    #[Route("/api/detailsCommande", methods: "POST")]
    function create(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $plat = $em->getRepository(Plat::class)->find($data['idPlat']);
        $commande = $em->getRepository(Commande::class)->find($data['idCommande']);

        if (!$plat || !$commande) {
            return $this->json(['error' => 'Plat ou Ingrédient non trouvé'], 404);
        }

        $detailsCommande = new DetailsCommande();
        $detailsCommande->setIdPlat($plat);
        $detailsCommande->setIdCommande($commande);
        $detailsCommande->setStatut($data['statut']);

        $em->persist($detailsCommande);
        $em->flush();

        return $this->json($detailsCommande, 200, [], [
            'groups' => ['detailsCommande.create']
        ]);
    }
    

    #[Route("/api/detailsCommande/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        DetailsCommandeRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $detailsCommande = $repository->find($id);
        if (!$detailsCommande) {
            throw new NotFoundHttpException('DetailsCommande non trouvé');
        }

        $updatedDetailsCommande = $serializer->deserialize(
            $request->getContent(),
            DetailsCommande::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $detailsCommande,  'groups' => ['detailsCommande.update']]
        );

        $em->persist($updatedDetailsCommande);
        $em->flush();
        return $this->json($updatedDetailsCommande, 200, [], [
            'groups' => ['detailsCommande.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/detailsCommande/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        DetailsCommandeRepository $repository,
    ) {
        $detailsCommande = $repository->find($id);
        if (!$detailsCommande) {
            throw new NotFoundHttpException('DetailsCommande non trouvé');
        }

        $deleteService->hardDelete($detailsCommande);

        return new Response(null, 204);
    }
    #[Route("/api/detailsCommande", methods: ["POST"])]
    function createMulti(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $plat = $em->getRepository(Plat::class)->find($data['idPlat']);
        $commande = $em->getRepository(Commande::class)->find($data['idCommande']);

        if (!$plat || !$commande) {
            return $this->json(['error' => 'Plat ou Commande non trouvé'], 404);
        }

        if (!isset($data['quantite']) || $data['quantite'] <= 0) {
            return $this->json(['error' => 'Quantité invalide'], 400);
        }

        $detailsCommandeList = [];
        
        for ($i = 0; $i < $data['quantite']; $i++) {
            $detailsCommande = new DetailsCommande();
            $detailsCommande->setIdPlat($plat);
            $detailsCommande->setIdCommande($commande);
            $detailsCommande->setStatut(-1);
            $detailsCommande->setDeletedAt(null);

            $em->persist($detailsCommande);
            $detailsCommandeList[] = $detailsCommande;
        }

        $em->flush();

        return $this->json($detailsCommandeList, 201, [], [
            'groups' => ['detailsCommande.create']
        ]);
    }

    #[Route("/api/detailsCommande/{id}/utilisateur", methods: ["POST"])]
function createMultiByUser(
    int $id,
    Request $request,
    EntityManagerInterface $em,
    UtilisateurRepository $userRepo,
    CommandeRepository $commandeRepo,
    PaiementRepository $paiementRepo,
    PlatRepository $platRepo
) {
    $data = json_decode($request->getContent(), true);

    // Vérifier si l'utilisateur existe
    $utilisateur = $userRepo->find($id);
    if (!$utilisateur) {
        return $this->json(['error' => 'Utilisateur non trouvé'], 404);
    }

    // Récupérer ou créer la commande actuelle
    $paiement = $paiementRepo->findOneBy(
        ['statut' => -1, 'idCommande.idUtilisateur' => $id], 
        ['id' => 'DESC']
    );

    if ($paiement) {
        $commande = $paiement->getIdCommande();
    } else {
        $commande = new Commande();
        $commande->setIdUtilisateur($utilisateur);
        $commande->setDt(new \DateTime());

        $em->persist($commande);
        $em->flush();

        // Créer le paiement associé
        $paiement = new Paiement();
        $paiement->setIdCommande($commande);
        $paiement->setTotal(0);
        $paiement->setStatut(-1);
        $paiement->setDeletedAt(null);

        $em->persist($paiement);
        $em->flush();
    }

    // Vérifier si le plat existe
    $plat = $platRepo->find($data['idPlat']);
    if (!$plat) {
        return $this->json(['error' => 'Plat non trouvé'], 404);
    }

    // Vérifier la quantité
    if (!isset($data['quantite']) || $data['quantite'] <= 0) {
        return $this->json(['error' => 'Quantité invalide'], 400);
    }

    // Ajouter plusieurs détails de commande
    $detailsCommandeList = [];
    for ($i = 0; $i < $data['quantite']; $i++) {
        $detailsCommande = new DetailsCommande();
        $detailsCommande->setIdPlat($plat);
        $detailsCommande->setIdCommande($commande);
        $detailsCommande->setStatut(-1);
        $detailsCommande->setDeletedAt(null);

        $em->persist($detailsCommande);
        $detailsCommandeList[] = $detailsCommande;
    }

    $em->flush();

    return $this->json($detailsCommandeList, 201, [], [
        'groups' => ['detailsCommande.create']
    ]);
}

}
