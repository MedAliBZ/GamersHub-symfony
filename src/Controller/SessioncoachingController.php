<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Sessioncoaching;
use App\Form\SessioncoachingType;
use App\Repository\CoachRepository;
use App\Repository\SessioncoachingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sessioncoaching")
 */
class SessioncoachingController extends AbstractController
{
    /**
     * @Route("/", name="sessioncoaching_index", methods={"GET"})
     */
    public function index(SessioncoachingRepository $sessioncoachingRepository,CoachRepository $repository): Response
    {   $coach=$repository->findOneBy(array('user'=>$this->getUser()));
        return $this->render('sessioncoaching/index.html.twig', [
            'sessioncoachings' => $sessioncoachingRepository->findAll(),
            'user' => $this->getUser(),
            'coach' =>$coach

        ]);
    }

    /**
     * @Route("/newsession", name="sessioncoaching_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager ,CoachRepository $repository): Response
    {   $coach=$repository->findOneBy(array('user'=>$this->getUser()));
        $sessioncoaching = new Sessioncoaching();
        $form = $this->createForm(SessioncoachingType::class, $sessioncoaching);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sessioncoaching->setCoach($coach);
            $entityManager->persist($sessioncoaching);
            $entityManager->flush();

            return $this->redirectToRoute('sessioncoaching_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sessioncoaching/_form.html.twig', [
            'sessioncoaching' => $sessioncoaching,
            'formsession' => $form->createView(),
            'coach'=>$coach



        ]);
    }



    /**
     * @Route("/{id}/edit", name="sessioncoaching_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sessioncoaching $sessioncoaching, EntityManagerInterface $entityManager,CoachRepository $repository): Response
    {   $coach=$repository->findOneBy(array('user'=>$this->getUser()));
        $form = $this->createForm(SessioncoachingType::class, $sessioncoaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('sessioncoaching_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sessioncoaching/_form.html.twig', [
            'sessioncoaching' => $sessioncoaching,
            'formsession' => $form->createView(),
            'coach'=>$coach
        ]);
    }

    /**
     * @Route("/{id}/delete", name="sessioncoaching_delete", methods={"GET"})
     */
    public function delete(Sessioncoaching $sessioncoaching): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($sessioncoaching);
        $em->flush();
        return $this->redirectToRoute('sessioncoaching_index');

    }

    /**
     * @Route("/admin/sessioncoaching", name="showsession", methods={"GET"})
     */
    public function show(SessioncoachingRepository $sessioncoachingRepository,CoachRepository $repository): Response
    {
        $coach=$repository->findOneBy(array('user'=>$this->getUser()));
        return $this->render('sessioncoaching/sessionback.html.twig', [
            'sessioncoachings' => $sessioncoachingRepository->findAll(),
            'user' => $this->getUser(),
            'coach' =>$coach]);
    }

    /**
     * @Route("/admin/{id}/edit", name="session_edit_back", methods={"GET", "POST"})
     */
    public function editBack(Request $request, Sessioncoaching $sessioncoaching, EntityManagerInterface $entityManager,CoachRepository $repository): Response
    {   $coach=$repository->findOneBy(array('user'=>$this->getUser()));
        $form = $this->createForm(SessioncoachingType::class, $sessioncoaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('showsession', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sessioncoaching/sessioneditback.html.twig', [
            'sessioncoaching' => $sessioncoaching,
            'formsessionback' => $form->createView(),
            'coach'=>$coach,
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/admin/{id}/delete", name="session_delete_back", methods={"GET"})
     */
    public function deleteBack(Sessioncoaching $sessioncoaching): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($sessioncoaching);
        $em->flush();
        return $this->redirectToRoute('showsession');

    }
}
