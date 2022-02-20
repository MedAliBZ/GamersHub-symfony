<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Form\CoachType;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/coach")
 */
class CoachController extends AbstractController
{
    /**
     * @Route("/", name="coach_index", methods={"GET"})
     */
    public function index(CoachRepository $coachRepository): Response
    {
        return $this->render('coach/index.html.twig', [
            'coachs' => $coachRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/new", name="coach_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coach = new Coach();
        $form = $this->createForm(CoachType::class, $coach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coach->setUser($this->getUser());
            $entityManager->persist($coach);
            $entityManager->flush();

            return $this->redirectToRoute('coach_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coach/_form.html.twig', [
            'coach' => $coach,
            'user' => $this->getUser(),
            'formcoach' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="coach_show", methods={"GET"})
     */
    public function show(Coach $coach): Response
    {
        return $this->render('coach/show.html.twig', [
            'coach' => $coach,
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="coach_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Coach $coach, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoachType::class, $coach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('coach_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coach/_form.html.twig', [
            'coach' => $coach,
            'formcoach' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="coach_delete", methods={"GET"})
     */
    public function delete(Coach $coach): Response
    {
          $em=$this->getDoctrine()->getManager();
          $em->remove($coach);
          $em->flush();
        return $this->redirectToRoute('coach_index');
    }
}
