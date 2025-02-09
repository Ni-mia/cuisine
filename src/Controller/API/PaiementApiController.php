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
use App\Repository\CommandeRepository;
use App\Repository\DetailsCommandeRepository;
use App\Repository\PrixRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    #[Route("/api/paiement/{id}/valider", methods: ["PUT"])]
    function validerCommande(int $id, PaiementRepository $repository, EntityManagerInterface $em)
    {
        $paiement = $repository->findOneBy(['idCommande' => $id]);

        if (!$paiement) {
            return $this->json(['error' => 'Aucun paiement trouvé pour cette commande'], 404);
        }

        // Mise à jour du statut à 0
        $paiement->setStatut(0);
        $em->persist($paiement);
        $em->flush();

        return $this->json([
            'message' => 'Commande validée avec succès',
            'idCommande' => $id,
            'statut' => $paiement->getStatut()
        ], 200);
    }
    //prix total commande
    #[Route("/api/commande/{id}/total", methods: ["GET"])]
    function calculerTotalCommande(int $id, CommandeRepository $commandeRepo, DetailsCommandeRepository $detailsRepo, PrixRepository $prixRepo, EntityManagerInterface $em, PaiementRepository $paiementRepo)
    {
        $commande = $commandeRepo->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], 404);
        }

        // Récupérer tous les détails de la commande
        $detailsCommandes = $detailsRepo->findBy(['idCommande' => $commande]);

        if (!$detailsCommandes) {
            return $this->json(['error' => 'Aucun détail trouvé pour cette commande'], 404);
        }

        $total = 0;
        $deprecated = false;

        foreach ($detailsCommandes as $detail) {
            $plat = $detail->getIdPlat();

            // Récupérer le prix actuel du plat
            $prix = $prixRepo->createQueryBuilder('p')
                ->where('p.idPlat = :plat')
                ->andWhere(':today BETWEEN p.date_debut AND p.date_fin')
                ->setParameter('plat', $plat)
                ->setParameter('today', new \DateTime())
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            if (!$prix) {
                // Si aucun prix actif, prendre le dernier prix inséré
                $prix = $prixRepo->findOneBy(['idPlat' => $plat], ['id' => 'DESC']);
                $deprecated = true;
            }

            if (!$prix) {
                return $this->json(['error' => "Aucun prix trouvé pour le plat ID {$plat->getId()}"], 400);
            }

            // Ajouter au total (quantité * prix)
            $total += $prix->getMontant();
        }

        // Mettre à jour le paiement
        $paiement = $paiementRepo->findOneBy(['idCommande' => $commande]);

        if ($paiement) {
            $paiement->setTotal($total);
            $em->persist($paiement);
            $em->flush();
        }

        return $this->json([
            'idCommande' => $commande->getId(),
            'total' => $total,
            'message' => $deprecated ? 'Attention : Certains prix sont obsolètes.' : 'Prix mis à jour avec succès.'
        ], 200);
    }


    #[Route("/api/chiffre-affaire/par-jour", methods: ["GET"])]
    public function chiffreAffaireParJour(PaiementRepository $paiementRepo): JsonResponse
    {
        // 🔹 Récupérer tous les paiements (non supprimés)
        $paiements = $paiementRepo->createQueryBuilder('p')
            ->select('p.dt, p.Total')
            ->where('p.deletedAt IS NULL') // Ignorer les paiements supprimés
            ->getQuery()
            ->getResult();

        $chiffreAffaireParJour = [];

        foreach ($paiements as $paiement) {
            $jour = $paiement['dt']->format('Y-m-d');
            $montant = (float) $paiement['Total']; // Convertir en float pour éviter les erreurs

            if (!isset($chiffreAffaireParJour[$jour])) {
                $chiffreAffaireParJour[$jour] = 0;
            }
            $chiffreAffaireParJour[$jour] += $montant; // Ajouter le montant au total du jour
        }

        return $this->json(array_map(function ($jour, $total) {
            return ['jour' => $jour, 'chiffreAffaire' => $total];
        }, array_keys($chiffreAffaireParJour), $chiffreAffaireParJour), 200);
    }

}
