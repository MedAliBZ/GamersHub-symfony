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


class SessioncoachingController extends AbstractController
{
    /**
     * @Route("/sessioncoaching", name="sessioncoaching_index", methods={"GET"})
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
     * @Route("/sessioncoaching/newsession", name="sessioncoaching_new", methods={"GET", "POST"})
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
     * @Route("/sessioncoaching/{id}/edit", name="sessioncoaching_edit", methods={"GET", "POST"})
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
     * @Route("/sessioncoaching/{id}/delete", name="sessioncoaching_delete", methods={"GET"})
     */
    public function delete(Sessioncoaching $sessioncoaching): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($sessioncoaching);
        $em->flush();
        return $this->redirectToRoute('sessioncoaching_index');

    }
    /**
     * @Route("/sessioncoaching/{id}/calendar", name="calendrier",methods={"GET"})
     */
    public function showcalendar(SessioncoachingRepository $calendar,$id)
    {
        $event=$calendar->find($id);

        $rdvs[]=[
            'id'=>$event->getId(),
            'user'=>$event->getUser(),
            'coach'=>$event->getCoach(),
            'price'=>$event->getPrix(),
            'start'=>$event->getDateDebut()->format('Y-m-d'),
            'end'=>$event->getDateFin()->format('Y-m-d'),
            'title'=>$event->getUser()->getUsername()."          Description:".$event->getDescription(),
            'backgroundColor'=>$event->getBackgroundColor(),
            'borderColor'=>$event->getBorderColor(),
            'textColor'=>$event->getTextColor()
        ];

        $data = json_encode($rdvs);
        return $this->render('sessioncoaching/calendrier.html.twig',compact('data'));
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
     * @Route("/admin/sessioncoaching/{id}/edit", name="session_edit_back", methods={"GET", "POST"})
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
     * @Route("/admin/sessioncoaching/{id}/delete", name="session_delete_back", methods={"GET"})
     */
    public function deleteBack(Sessioncoaching $sessioncoaching): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($sessioncoaching);
        $em->flush();
        return $this->redirectToRoute('showsession');

    }
}
