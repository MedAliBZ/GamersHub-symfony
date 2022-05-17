<?php

namespace App\Controller;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\File as FileFile;

class PlayerApiController extends AbstractController
{
    /**
     * @Route("/api/AllPlayer", name="api_AllPlayer")
     */

    public function AllPlayer(NormalizerInterface $Normalizer): Response
    {
        $repository = $this->getDoctrine()->getRepository(Player::class);
        $player = $repository->findAll();
        $jsonContent = $Normalizer->normalize($player, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent),
        200,
        ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    
    }

    /**
     * @Route("/api/getPlayerById/{id}", name="api_getPlayer")
     */

    public function getPlayerById(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Player::class);
        $p = $repository->find($id);
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/deletePlayer/{id}", name="api_deletePlayer")
     */

    public function delete(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Player::class);
        $p = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($p);
        $em->flush();
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response("player deleted" . json_encode($jsonContent));
    }

    /**
     * @Route("/api/createPlayer", name="api_createPlayer")
     */
    public function create(Request $request, NormalizerInterface $Normalizer)
    {
        $player = new Player();

        $em = $this->getDoctrine()->getManager();
       // $user = $em->getRepository(User::class)->find($request->get('id'));


        $player->setRank($request->get('rank'));
        $user = $em->getRepository(User::class)->find($request->get('user'));
        $player->setUser($user);


       /* $file = new FileFile($request->get('image'));


        $filename = md5(uniqid()) . '.'.$file->guessExtension();
        $file->move($this->getParameter('player_image_directory'), $filename);


        $player->setImage($filename);*/

        $em->persist($player);
        $em->flush();

        $jsonContent = $Normalizer->normalize($player, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent),
        200,
        ['Accept' => 'application/json',
        'Content-Type' => 'application/json']);
        
    }


    /**
     * @Route("/api/updatePlayer/{id}", name="api_updatePlayer")
     */
    public function update(Request $request, NormalizerInterface $Normalizer, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $player = $em->getRepository(Player::class)->find($id);
   
        $player->setRank($request->get('rank'));



        $em->flush();

        $jsonContent = $Normalizer->normalize($player, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
