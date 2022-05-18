<?php

namespace App\Controller;

use App\Entity\Tournaments;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\File as FileFile;

class TournamentsApiController extends AbstractController
{
    /**
     * @Route("/api/AllTournaments", name="api_AllTournaments")
     */

    public function AllTournaments(NormalizerInterface $Normalizer): Response
    {
        $repository = $this->getDoctrine()->getRepository(Tournaments::class);
        $tournaments = $repository->findAll();
        $jsonContent = $Normalizer->normalize($tournaments, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent),
        200,
        ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    
    }

    /**
     * @Route("/api/getTournamentsById/{id}", name="api_getTournaments")
     */

    public function getTournamentsById(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Tournaments::class);
        $p = $repository->find($id);
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/deleteTournaments/{id}", name="api_deleteTournaments")
     */

    public function delete(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Tournaments::class);
        $p = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($p);
        $em->flush();
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response("tournaments deleted" . json_encode($jsonContent));
    }

    /**
     * @Route("/api/createTournaments", name="api_createTournaments")
     */
    public function create(Request $request, NormalizerInterface $Normalizer)
    {
        $tournaments = new Tournaments();

        $em = $this->getDoctrine()->getManager();
       // $user = $em->getRepository(User::class)->find($request->get('id'));


        $tournaments->setName($request->get('name'));
        $tournaments->setDecription($request->get('description'));
        $tournaments->setTeamSize($request->get('teamSize'));
        $tournaments->setImages("game-img-2-622725cb244d1.png");

        $em->persist($tournaments);
        $em->flush();

        $jsonContent = $Normalizer->normalize($tournaments, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent),
        200,
        ['Accept' => 'application/json',
        'Content-Type' => 'application/json']);
        
    }


    /**
     * @Route("/api/updateTournaments/{id}", name="api_updateTournaments")
     */
    public function update(Request $request, NormalizerInterface $Normalizer, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $tournaments = $em->getRepository(Tournaments::class)->find($id);
   
        $tournaments->setName($request->get('name'));
        $tournaments->setDecription($request->get('description'));
        $tournaments->setTeamSize($request->get('teamSize'));


        $em->flush();

        $jsonContent = $Normalizer->normalize($tournaments, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
