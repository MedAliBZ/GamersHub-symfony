<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Form\LivreurType;
use App\Repository\LivreurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/livreur")
 */
class LivreurController extends AbstractController
{
    /**
     * @Route("/", name="livreur_index", methods={"GET"})
     */
    public function index(LivreurRepository $livreurRepository): Response
    {
        return $this->render('livreur/index.html.twig', [ 
            'user' => $this->getUser(),
            'livreurs' => $livreurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="livreur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livreur = new Livreur();
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livreurs= $entityManager->getRepository(Livreur::class)->findAll();
            $entityManager->persist($livreur);
            $entityManager->flush();

            return $this->redirectToRoute('livreur_index', [
                'user' => $this->getUser(),
                'livreurs' => $livreurs,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livreur/new.html.twig', [
            'user' => $this->getUser(),
            'livreur' => $livreur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livreur_show", methods={"GET"})
     */
    public function show(Livreur $livreur): Response
    {
        return $this->render('livreur/show.html.twig', [
            'user' => $this->getUser(),
            'livreur' => $livreur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="livreur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Livreur $livreur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livreurs= $entityManager->getRepository(Livreur::class)->findAll();
            $entityManager->flush();

            return $this->redirectToRoute('livreur_index', [
                'user' => $this->getUser(),
                'livreurs' => $livreurs,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livreur/edit.html.twig', [
            'user' => $this->getUser(),
            'livreur' => $livreur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livreur_delete", methods={"POST"})
     */
    public function delete(Request $request, Livreur $livreur, EntityManagerInterface $entityManager): Response
    {
        $livreurs= $entityManager->getRepository(Livreur::class)->findAll();
        if ($this->isCsrfTokenValid('delete'.$livreur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livreur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livreur_index', [
            'user' => $this->getUser(),
                'livreurs' => $livreurs,
        ], Response::HTTP_SEE_OTHER);
    }
}
