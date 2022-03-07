<?php

namespace App\Controller;
use App\Entity\User;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PlayerController extends AbstractController
{
    /**
     * @Route("/players/", name="player_index", methods={"GET"})
     */
    public function index(PlayerRepository $playerRepository): Response
    {
        
        return $this->render('player/index.html.twig', [
            'players' => $playerRepository->findAll(),
            'user' => $this->getUser() 
        ]);
    }

    /**
     * @Route("/players/newPlayer", name="player_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $player->setUser($this->getUser());
            $entityManager->persist($player);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('player/becomePlayer.html.twig', [
            'player' => $player,
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("admin/Players/showPlayer", name="player_show", methods={"GET"})
     */
    public function show(): Response
    {
        $player = new Player();
        $repo =$this->getDoctrine()->getRepository(Player::class);
        return $this->render('player/backPlayer.html.twig', [
            'user' => $this->getUser(),
            'player' => $player,
            'playersList'=> $repo->findAll()

        ]);
    }

    /**
     * @Route("admin/Players/{id}/edit", name="player_edit", methods={"GET", "POST"})
     */
    public function editPlayer(Request $request, Player $player): Response
    {
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);
        $repo =$this->getDoctrine()->getRepository(Player::class);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
                $em->persist($player);
                $em->flush();

            return $this->redirectToRoute('player_show');
        }

        return $this->render('player/backPlayerUpdate.html.twig', [
            'user' => $this->getUser(),
            'player' => $player,
            'form' => $form->createView(),
            'playersList'=> $repo->findAll()
        ]);
    }

    /**
     * @Route("admin/players/{id}", name="player_delete")
     */
    public function deletePlayer(Player $player): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($player);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Player::class);
        return $this->redirectToRoute("player_show");
    }
}
