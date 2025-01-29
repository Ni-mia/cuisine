<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Service\DeleteService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    #[Route(name: 'project.index', methods: ['GET'])]
    public function index(Request $request,ProjectRepository $repository,PaginatorInterface $paginator): Response
    {
        // Récupérer les valeurs de recherche et de filtre min/max
        $searchTitle = $request->query->get('search', '');

        // Appeler la méthode du repository pour filtrer les projects
        // $tasks = $repository->findByFilters($this->isGranted('ROLE_ADMIN'), $searchTitle, $minEstimate, $maxEstimate);
        $projects = $paginator->paginate(
            $repository->findByFilters($this->isGranted('ROLE_ADMIN'), $searchTitle),
            $request->query->getInt('page', 1), // Numéro de la page
            10 // Nombre d'éléments par page
        );

        // return $this->render('project/index.html.twig', [
        //     'projects' => $repository->findAll(),
        // ]);

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'searchTitle' => $searchTitle,
        ]);
    }

    #[Route('/new', name: 'project.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash('success','niditra ilay izy');
            return $this->redirectToRoute('project.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project.show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        $tasks = $project->getTasks(); 

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/{id}/edit', name: 'project.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('project.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project.delete', methods: ['POST'])]
    // public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    public function delete(Project $project, DeleteService $deleteService): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->getPayload()->getString('_token'))) {
        //     $entityManager->remove($project);
        //     $entityManager->flush();
        // }

        // return $this->redirectToRoute('project.index', [], Response::HTTP_SEE_OTHER);
        // Si l'utilisateur est admin, on effectue une suppression hard
        if ($this->isGranted('ROLE_ADMIN')) {
            $deleteService->hardDelete($project);
            $this->addFlash('success', 'project supprimée définitivement.');
        }
        // Sinon, on effectue une soft delete
        else {
            if ($project->isDeleted()) {
                throw new AccessDeniedException('Vous ne pouvez pas supprimer une project déjà supprimée.');
            }
            $deleteService->softDelete($project);
            $this->addFlash('success', 'project supprimée temporairement.');
        }

        return $this->redirectToRoute('project.index');
    }


}
