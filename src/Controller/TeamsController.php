<?php

namespace App\Controller;

use App\Entity\Teams;
use App\Form\TeamsType;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teams")
 */
class TeamsController extends AbstractController
{
    /**
     * @Route("/", name="teams_index")
     */
    public function index(TeamsRepository $teamsRepository): Response
    {
        $team = new Teams();
        $repo =$this->getDoctrine()->getRepository(Teams::class);
    
        return $this->render('teams/Teams.html.twig', [
            
            'user' => $this->getUser(),
            'team' => $team,
            'teamsList'=> $repo->findAll()
        ]);
    }

    /**
     * @Route("/new", name="teams_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Teams();
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('teams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teams/new.html.twig', [
            'user' => $this->getUser(),

            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Admin/showTeams", name="teams_show")
     */
    public function show(): Response
    {   $team = new Teams();
        $repo =$this->getDoctrine()->getRepository(Teams::class);
        return $this->render('teams/TeamsBack.html.twig', [
            'user' => $this->getUser(),
            'team' => $team,
            'teamsList'=> $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/teams/{id}/edit", name="teams_edit")
     */
    public function edit(Request $request, Teams $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('teams_show');
        }

        return $this->render('teams/TeamUpdate.html.twig', [
            'user' => $this->getUser(),
            'team' => $team,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/teams/{id}/delete", name="teams_delete")
     */
    public function delete(Teams $team): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Teams::class);
        return $this->redirectToRoute("teams_show");
       
}
}