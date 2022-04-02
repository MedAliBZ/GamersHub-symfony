<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Game;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/api")
 */
class CoachAPIController extends AbstractController
{
    /**
     * @Route("/coachs", name="api_coach")
     */
    public function allCoachs(NormalizerInterface $normalizer): Response
    {
        $coachs = $this->getDoctrine()->getRepository(Coach::class)->findAll();
        $jsonContent = $normalizer->normalize($coachs, 'json', ['groups' => 'api:coach']);

        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
    }

//    /**
//     * @Route("/register", name="api_register", methods={"POST"})
//     */
//    public function register(Request $request, NormalizerInterface $normalizer): Response
//    {
//        $em = $this->getDoctrine()->getManager();
//        $coach =new Coach();
//        $coach->setUser()
//
//    }
    /**
     * @Route("/coach/delete", name="api_coach_delete")
     */

    public function delete(Request $request, NormalizerInterface $normalizer): Response
    {
        if (!$request->query->get('username'))
            return new Response(
                '{"error": "Missing username."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $coach = $this->getDoctrine()->getRepository(Coach::class)->findOneBy(["user"=>$this->getDoctrine()->getRepository(User::class)->findOneBy(["username"=>$request->query->get('username')])]);
        if ($coach == null)
            return new Response(
                '{"error": "Coach not found."}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($coach);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->query->get('username')} deleted.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }
    /**
     * @Route("/coach/add", name="api_coach_add", methods={"POST"})
     */
    public function registerCoach(Request $request, NormalizerInterface $normalizer): Response
    {
        if (!($request->request->get('username') && $request->request->get('description') && $request->request->get('game') ))
            return new Response(
                '{"error": "Missing username or game or description."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $coach = new Coach();
        $coach->setUser($this->getDoctrine()->getRepository(User::class)->findOneBy(["username"=>$request->request->get('username')]));
        $coach->setGame($this->getDoctrine()->getRepository(Game::class)->findOneBy(["name"=>$request->request->get('game')]));
        $coach->setDescription($request->request->get('description'));
        $em->persist($coach);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->query->get('id')} created.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/coach/update", name="api_coach_update", methods={"POST"})
     */
    public function updateCoach(Request $request, NormalizerInterface $normalizer): Response
    {
        if (!($request->request->get('username') && $request->request->get('description') && $request->request->get('game') ))
            return new Response(
                '{"error": "Missing username or game or description."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $coach = $this->getDoctrine()->getRepository(Coach::class)->findOneBy(["user"=>$this->getDoctrine()->getRepository(User::class)->findOneBy(["username"=>$request->request->get('username')])]);
        $coach->setGame($this->getDoctrine()->getRepository(Game::class)->findOneBy(["name"=>$request->request->get('game')]));
        $coach->setDescription($request->request->get('description'));
        $em->persist($coach);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->query->get('id')} created.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }

}
