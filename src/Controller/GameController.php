<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game")
 */
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
            date_default_timezone_set('Europe/Paris');
            $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
            $game->setCreatedAt($dateTime);
            $game->setUpdatedAt($dateTime);
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
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/admin/game/{id}", name="game_delete")
     */
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if(is_file($this->getParameter('game_image_directory').'/'.$game->getImage())){
            unlink($this->getParameter('game_image_directory').'/'.$game->getImage());
        }
        $entityManager->remove($game);
        $entityManager->flush();
        return $this->redirectToRoute('gamesAdmin');
    }
}
