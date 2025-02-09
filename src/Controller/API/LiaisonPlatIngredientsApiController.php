<?php

namespace App\Controller\API;

use App\Entity\LiaisonPlatIngredients;
use App\Repository\LiaisonPlatIngredientsRepository;
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
use App\Entity\Ingredients;
use App\Entity\Plat;
use App\Repository\IngredientsRepository;
use App\Repository\PlatRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class LiaisonPlatIngredientsApiController extends AbstractController
{
    //create,edit,list,detail,delete
    #[Route("/api/liaisonPlatIngredients", methods: "GET")]
    // #[TokenRequired]
    function list(LiaisonPlatIngredientsRepository $repository){
        $liaisonPlatIngredientslist = $repository->findAll();
        return $this->json($liaisonPlatIngredientslist,200,[],[
            'groups' => ['liaisonPlatIngredients.list']
        ]);
    }
    #[Route("/api/liaisonPlatIngredients2", methods: "GET")]
    function list2(EntityManagerInterface $em)
    {
        $query = $em->createQuery(
            "SELECT l.id AS id, p.id AS idPlat,p.nom AS nomPlat, i.id as idIngredients, i.nom AS nomIngredients, l.quantite AS quantite, l.deletedAt as deletedAt 
            FROM App\Entity\LiaisonPlatIngredients l
            JOIN l.idPlat p
            JOIN l.idIngredients i"
        );

        $result = $query->getResult();

        return $this->json($result, 200);
    }

    #[Route("/api/liaisonPlatIngredients/{id<\d+>}", methods: "GET")]
    function detail(LiaisonPlatIngredientsRepository $repository,int $id){
        $liaisonPlatIngredients = $repository->findById($id);
        return $this->json($liaisonPlatIngredients,200,[],[
            'groups' => ['liaisonPlatIngredients.list']
        ]);
    }

    #[Route("/api/liaisonPlatIngredients", methods: "POST")]
    function create(
        Request $request,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent(), true);

        $plat = $em->getRepository(Plat::class)->find($data['idPlat']);
        $ingredient = $em->getRepository(Ingredients::class)->find($data['idIngredients']);

        if (!$plat || !$ingredient) {
            return $this->json(['error' => 'Plat ou Ingrédient non trouvé'], 404);
        }

        $liaisonPlatIngredients = new LiaisonPlatIngredients();
        $liaisonPlatIngredients->setIdPlat($plat);
        $liaisonPlatIngredients->setIdIngredients($ingredient);
        $liaisonPlatIngredients->setQuantite($data['quantite']);

        $em->persist($liaisonPlatIngredients);
        $em->flush();

        return $this->json($liaisonPlatIngredients, 200, [], [
            'groups' => ['liaisonPlatIngredients.create']
        ]);
    }

    

    #[Route("/api/liaisonPlatIngredients/{id}", methods: "PUT")]
    function edit(
        int $id,
        Request $request,
        LiaisonPlatIngredientsRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer){
        
        $liaisonPlatIngredients = $repository->find($id);
        if (!$liaisonPlatIngredients) {
            throw new NotFoundHttpException('LiaisonPlatIngredients non trouvé');
        }

        $updatedLiaisonPlatIngredients = $serializer->deserialize(
            $request->getContent(),
            LiaisonPlatIngredients::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $liaisonPlatIngredients,  'groups' => ['liaisonPlatIngredients.update']]
        );

        $em->persist($updatedLiaisonPlatIngredients);
        $em->flush();
        return $this->json($updatedLiaisonPlatIngredients, 200, [], [
            'groups' => ['liaisonPlatIngredients.show']
        ]);
    }
    //faire le softdelete
    #[Route("/api/liaisonPlatIngredients/{id}", methods: "DELETE")]
    public function delete(
        int $id,
        DeleteService $deleteService,
        LiaisonPlatIngredientsRepository $repository,
    ) {
        $liaisonPlatIngredients = $repository->find($id);
        if (!$liaisonPlatIngredients) {
            throw new NotFoundHttpException('LiaisonPlatIngredients non trouvé');
        }

        $deleteService->hardDelete($liaisonPlatIngredients);

        return new Response(null, 204);
    }

    /*make recette*/
    #[Route("/api/liaisonPlatIngredients/makeRecette", methods: "POST")]
    function makeRecette(Request $request, LiaisonPlatIngredientsRepository $repository, EntityManagerInterface $em, PlatRepository $platRepo, IngredientsRepository $ingRepo)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['idPlat'], $data['quantites'], $data['idIngredients'])) {
            return $this->json(['error' => 'Paramètres manquants'], 400);
        }

        if (count($data['quantites']) !== count($data['idIngredients'])) {
            return $this->json(['error' => 'Les tableaux quantites et idIngredients doivent avoir la même longueur'], 400);
        }

        $plat = $platRepo->find($data['idPlat']);
        if (!$plat) {
            return $this->json(['error' => 'Plat non trouvé'], 404);
        }

        $liaisonList = [];

        foreach ($data['idIngredients'] as $index => $idIngredient) {
            $ingredient = $ingRepo->find($idIngredient);
            if (!$ingredient) {
                return $this->json(['error' => "Ingrédient ID $idIngredient non trouvé"], 404);
            }

            $liaison = new LiaisonPlatIngredients();
            $liaison->setIdPlat($plat);
            $liaison->setIdIngredients($ingredient);
            $liaison->setQuantite($data['quantites'][$index]);

            $em->persist($liaison);
            $liaisonList[] = $liaison;
        }

        $em->flush();

        return $this->json($liaisonList, 201, [], [
            'groups' => ['liaisonPlatIngredients.create']
        ]);
    }
    //getRecette
    #[Route("/api/liaisonPlatIngredients/getRecette/{idPlat}", methods: "GET")]
    function getRecette(int $idPlat, LiaisonPlatIngredientsRepository $repository): JsonResponse
    {
        // Récupérer les liaisons entre le plat et les ingrédients
        $liaisons = $repository->findBy(['idPlat' => $idPlat]);

        // Vérifier si le plat a des ingrédients
        if (!$liaisons) {
            return $this->json(['error' => 'Aucun ingrédient trouvé pour ce plat'], 404);
        }

        // Construire la réponse
        $recette = array_map(function ($liaison) {
            return [
                'idPlat' => $liaison->getId(),
                'idIngredient' => $liaison->getIdIngredients()->getId(),
                'nomIngredient' => $liaison->getIdIngredients()->getNom(),
                'quantite' => $liaison->getQuantite(),
            ];
        }, $liaisons);

        return $this->json($recette);
    }

    //getRecetteAll
    #[Route("/api/liaisonPlatIngredients/getRecetteAll", methods: "GET")]
    function getRecetteAll(LiaisonPlatIngredientsRepository $repository): JsonResponse
    {
        // Récupérer toutes les liaisons plat-ingrédients
        $liaisons = $repository->findAll();

        // Vérifier s'il y a des données
        if (!$liaisons) {
            return $this->json(['error' => 'Aucune recette trouvée'], 404);
        }

        // Organiser les données sous forme de recettes groupées par idPlat
        $recettes = [];

        foreach ($liaisons as $liaison) {
            $idPlat = $liaison->getIdPlat()->getId();
            if (!isset($recettes[$idPlat])) {
                $recettes[$idPlat] = [
                    'idPlat' => $idPlat,
                    'nomPlat' => $liaison->getIdPlat()->getNom(), // Si la classe Plat a un nom
                    'ingredients' => [],
                ];
            }

            $recettes[$idPlat]['ingredients'][] = [
                'idIngredient' => $liaison->getIdIngredients()->getId(),
                'nomIngredient' => $liaison->getIdIngredients()->getNom(),
                'quantite' => $liaison->getQuantite(),
            ];
        }

        // Convertir en tableau indexé pour le JSON
        return $this->json(array_values($recettes));
    }

}
