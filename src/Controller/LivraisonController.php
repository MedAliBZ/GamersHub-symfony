<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/livraison")
 */
class LivraisonController extends AbstractController
{
    /**
     * @Route("/", name="livraison_index", methods={"GET"})
     */
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraison/index.html.twig', [
            'user' => $this->getUser(),
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="livraison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraisons= $entityManager->getRepository(Livraison::class)->findAll();
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('livraison_index', [
                'user' => $this->getUser(),
                'livraisons' => $livraisons,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/new.html.twig', [
            'user' => $this->getUser(),
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livraison_show", methods={"GET"})
     */
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'user' => $this->getUser(),
            'livraison' => $livraison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="livraison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraisons= $entityManager->getRepository(Livraison::class)->findAll();
            $entityManager->flush();

            return $this->redirectToRoute('livraison_index', [
                'user' => $this->getUser(),
                'livraisons' => $livraisons,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/edit.html.twig', [
            'user' => $this->getUser(),
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livraison_delete", methods={"POST"})
     */
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $livraisons= $entityManager->getRepository(Livraison::class)->findAll();
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livraison_index', [
            'user' => $this->getUser(),
                'livraisons' => $livraisons,
        ], Response::HTTP_SEE_OTHER);
    }
}
