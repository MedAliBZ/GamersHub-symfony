<?php

namespace App\Controller;

use App\Entity\Tournaments;
use App\Form\TournamentsType;
use App\Repository\TournamentsRepository;

use App\Entity\Subscribe;
use App\Form\SubscribeType;
use App\Repository\SubscribeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;


class SubscribeController extends AbstractController
{
    /**
     * @Route("/subscribe/", name="app_subscribe_index", methods={"GET"})
     */
    public function index(SubscribeRepository $subscribeRepository): Response
    {
        return $this->render('subscribe/index.html.twig', [
            'subscribe' => $subscribeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/tournaments/subscribe/new/{id}", name="addSubscription", methods={"GET", "POST"})
     */
    public function newFromTournament(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $subscribe = new Subscribe();
        $tournament = $this->getDoctrine()->getRepository(Tournaments::Class)->find($id);
        $form = $this->createForm(SubscribeType::class, $subscribe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscribe->setTournament($tournament);
            $entityManager->persist($subscribe);
            $entityManager->flush();

            return $this->redirectToRoute('addSubscription', ['id' => $tournament->getId()]);
        }
        return $this->render('subscribe/Subscribe.html.twig', [
            'subscribe' => $subscribe,
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/back/subscribers", name="showSubscribed")
     */
    public function show(): Response
    {
        $subscribe = new Subscribe();
        $repo =$this->getDoctrine()->getRepository(Subscribe::class);
        return $this->render('subscribe/backSubscribe.html.twig', [
            'subscribe' => $subscribe,
            'user' => $this->getUser(),
            'subscribeList' => $repo->findAll(),

        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_subscribe_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Subscribe $subscribe, SubscribeRepository $subscribeRepository): Response
    {
        $form = $this->createForm(SubscribeType::class, $subscribe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscribeRepository->add($subscribe);
            return $this->redirectToRoute('app_subscribe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subscribe/edit.html.twig', [
            'subscribe' => $subscribe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="deleteSubscription", methods={"POST"})
     */
    public function delete(Subscribe $subscribe): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subscribe);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Subscribe::class);
        return $this->redirectToRoute("showSubscribed");
    }
}
