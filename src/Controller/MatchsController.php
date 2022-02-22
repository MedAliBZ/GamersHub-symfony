<?php

namespace App\Controller;

use App\Entity\Matchs;
use App\Form\MatchsType;
use App\Repository\MatchsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/matchs")
 */
class MatchsController extends AbstractController
{
    /**
     * @Route("/", name="matchs_index")
     */
    public function index(MatchsRepository $matchsRepository): Response
    {
        $match = new Matchs();
        $repo =$this->getDoctrine()->getRepository(Matchs::class);
        return $this->render('matchs/Match.html.twig', [
            'user' => $this->getUser(),
            'match'=>$match,
            'MatchsList'=> $repo->findAll()
        ]);
        
    }

    /**
     * @Route("/new", name="matchs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $match = new Matchs();
        $form = $this->createForm(MatchsType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($match);
            $entityManager->flush();

            return $this->redirectToRoute('matchs_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matchs/new.html.twig', [
            'user' => $this->getUser(),
            'match' => $match,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Admin/showMatchs", name="matchs_show", methods={"GET"})
     */
    public function show(): Response
    {
        $match = new Matchs();
        $repo =$this->getDoctrine()->getRepository(Matchs::class);
        return $this->render('matchs/MatchsBack.html.twig', [
            'user' => $this->getUser(),
            'match' => $match,
            'MatchsList'=> $repo->findAll()
            
        ]);
    }

    /**
     * @Route("/admin/matchs/{id}/edit", name="matchs_edit")
     */
    public function edit(Request $request, Matchs $match, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatchsType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('matchs_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matchs/MatchUpdate.html.twig', [
            'user' => $this->getUser(),
            'match' => $match,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("admin/matchs/{id}/delete", name="matchs_delete")
     */
    public function delete(Matchs $match): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($match);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Matchs::class);
        return $this->redirectToRoute("matchs_show");
       

}
}
