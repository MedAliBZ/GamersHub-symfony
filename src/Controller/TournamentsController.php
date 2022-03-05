<?php

namespace App\Controller;

use App\Entity\Tournaments;
use App\Form\TournamentsType;
use App\Repository\TournamentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Historique;


class TournamentsController extends AbstractController
{
    // /**
    //  * @Route("/test", name="tournaments_main", methods={"GET"})
    //  */
     //{
        // return $this->render('tournaments/backTournaments.html.twig', [
          //   'tournaments' => $tournamentsRepository->findAll(),
          //   'user' => $this->getUser()
        // ]);
    // }

    /**
     * @Route("/tournaments/", name="tournaments_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, TournamentsRepository $tournamentsRepository): Response
    {
        $tournament = new Tournaments();
        $repo =$this->getDoctrine()->getRepository(Tournaments::class);
        $form = $this->createForm(TournamentsType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tournament);
            $entityManager->flush();
            $historique=new Historique();
            $historique->setAction("add tourn");
            $historique->setModel("tournament");
            $entityManager->persist($historique);
            $entityManager->flush();
            return $this->redirectToRoute('tournaments_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tournaments/tournaments.html.twig', [
            'tournament' => $tournament,
            'tournaments' => $tournamentsRepository->findAll(),
            'user' => $this->getUser(),
            'form' => $form->createView(),
            'tournamentsList' => $repo->findAll(),
            

        ]);
    } 

     /**
     * @Route("/admin/tournaments/backTournaments", name="backTournaments")
     */
    public function back(Request $request, TournamentsRepository $tournamentsRepository): Response
    {
        $tournament = new Tournaments();
        $repo =$this->getDoctrine()->getRepository(Historique::class);
        $form = $this->createForm(TournamentsType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
                $em->persist($tournament);
                $em->flush();

            return $this->redirectToRoute('backTournaments', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tournaments/backTournaments.html.twig', [
            'tournament' => $tournament,
            'tournaments' => $tournamentsRepository->findAll(),
            'user' => $this->getUser(),
            'form' => $form->createView(),
            'historique' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournaments_show")
     */
    public function show(Tournaments $tournament): Response
    {

        $repo =$this->getDoctrine()->getRepository(Tournaments::class);
        return $this->render('tournaments/backTournaments.html.twig', [
            'tournament' => $tournament,
            'user' => $this->getUser(),
            'tournamentsList' => $repo->findAll(),

        ]);
    }

    /**
     * @Route("/admin/tournaments/{id}/edit", name="tournaments_edit")
     */
    public function edit(Request $request, Tournaments $tournament,EntityManagerInterface $entityManager) : Response
    {
        
        $form = $this->createForm(TournamentsType::class, $tournament);
        $form->handleRequest($request);
        $repo =$this->getDoctrine()->getRepository(Tournaments::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
                $entityManager->persist($tournament);
                $historique = new Historique();
                $historique->setAction("Edit tournament");
                $historique->setModel("tournament");
               
                $entityManager->persist($historique);
                $entityManager->flush();

            return $this->redirectToRoute("backTournaments");
        }

        return $this->render('tournaments/backTournamentUpdate.html.twig', [
            'user' => $this->getUser(),
            'tournament' => $tournament,
            'form' => $form->createView(),
            'tournamentList' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/Tournaments/{id}", name="tournaments_delete")
     */
    public function delete(Tournaments $tournament,EntityManagerInterface $entityManager): Response
    {
     
        $entityManager->remove($tournament);
        $entityManager->flush();
        $historique = new Historique();
        $historique->setAction("Delete tour");
        $historique->setModel("tournament");
      
        $entityManager->persist($historique);
        $entityManager->flush();

        $repo =$this->getDoctrine()->getRepository(Tournaments::class);
        return $this->redirectToRoute("backTournaments");
    }
}
