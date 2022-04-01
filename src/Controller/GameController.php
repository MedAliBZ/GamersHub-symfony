<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Form\GameUpdateType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/admin/games", name="gamesAdmin", methods={"GET"})
     */
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/showBack.html.twig', [
            'gamesList' => $gameRepository->findAll(),
            'user' => $this->getUser()
        ]);
    }
    /**
     * @Route("/game/{id}", name="gameById", methods={"GET"})
     */
    public function showGame(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game,
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/games", name="games", methods={"GET"})
     */
    public function showGames(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'gamesList' => $gameRepository->findAll(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/game/follow/{id}", name="followGame", methods={"GET"})
     */
    public function followGame(EntityManagerInterface $entityManager, Game $game, Request $req): Response
    {
        $game->addUser($this->getUser());
        $entityManager->flush();
        return $this->redirectToRoute("gameById",['id'=>$game->getId()]);
    }

    /**
     * @Route("/game/unfollow/{id}", name="unfollowGame", methods={"GET"})
     */
    public function unfollowGame(EntityManagerInterface $entityManager, Game $game): Response
    {
        $game->removeUser($this->getUser());
        $entityManager->flush();
        return $this->redirectToRoute("gameById",['id'=>$game->getId()]);
    }

    /**
     * @Route("/admin/game/new", name="game_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $game->getImage();
            $fileName = md5(uniqid()) . '.jpg';
            $game->setImage($fileName);
            $entityManager->persist($game);
            $entityManager->flush();
            $file->move($this->getParameter('game_image_directory'), $fileName);

            return $this->redirectToRoute('gamesAdmin');
        }

        return $this->render('game/new.html.twig', [
            'game' => $game,
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/game/{id}/edit", name="game_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        $oldGame = $game;
        $form = $this->createForm(GameUpdateType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["image"]->getData() != null) {
                if (is_file($this->getParameter('game_image_directory') . '/' . $oldGame->getImage())) {
                    unlink($this->getParameter('game_image_directory') . '/' . $oldGame->getImage());
                }
                $file = $form["image"]->getData();
                $fileName = md5(uniqid()) . '.jpg';
                $game->setImage($fileName);
                $file->move($this->getParameter('game_image_directory'), $fileName);
            }
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('gamesAdmin');
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/admin/game/delete/{id}", name="game_delete")
     */
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if (is_file($this->getParameter('game_image_directory') . '/' . $game->getImage())) {
            unlink($this->getParameter('game_image_directory') . '/' . $game->getImage());
        }
        $entityManager->remove($game);
        $entityManager->flush();
        return $this->redirectToRoute('gamesAdmin');
    }
}
