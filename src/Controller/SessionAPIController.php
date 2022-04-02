<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Sessioncoaching;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/api")
 */
class SessionAPIController extends AbstractController
{
    /**
     * @Route("/sessions", name="api_session")
     */
    public function allsession(NormalizerInterface $normalizer): Response
    {
        $session = $this->getDoctrine()->getRepository(Sessioncoaching::class)->findAll();
        $jsonContent = $normalizer->normalize($session, 'json', ['groups' => 'api:session']);

        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/session/delete", name="api_session_delete")
     */

    public function delete(Request $request, NormalizerInterface $normalizer): Response
    {
        if (!$request->query->get('id'))
            return new Response(
                '{"error": "Missing id."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $session = $this->getDoctrine()->getRepository(Sessioncoaching::class)->findOneBy(["id" => $request->query->get('id')]);
        if ($session == null)
            return new Response(
                '{"error": "session not found."}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($session);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->query->get('id')} deleted.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/session/add", name="api_session_add", methods={"POST"})
     */
    public function addSession(Request $request, NormalizerInterface $normalizer): Response
    {
        if (!($request->request->get('username') && $request->request->get('description') && $request->request->get('coachname')&& $request->request->get('prix')&& $request->request->get('date_debut')&& $request->request->get('date_fin') ))
            return new Response(
                '{"error": "Missing username or coachname or description or price of date debut or date fin."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $session = new Sessioncoaching();
        $session->setUser($this->getDoctrine()->getRepository(User::class)->findOneBy(["username"=>$request->request->get('username')]));
        $session->setCoach($this->getDoctrine()->getRepository(User::class)->findOneBy(["username"=>$request->request->get('coachname')])->getCoach());
        //dd($this->getDoctrine()->getRepository(User::class)->findOneBy(["username"=>$request->request->get('coachname')])->getCoach());
        $session->setDescription($request->request->get('description'));
        // date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', $request->request->get('date_debut')."0:0:0"));
        $session->setDateDebut(new \DateTime($request->request->get('date_debut')));
        $session->setDateFin(new \DateTime($request->request->get('date_fin')));
        $session->setPrix($request->request->get('prix'));
        $session->setBackgroundColor("#00000");
        $session->setBorderColor('#00000');
        $session->setTextColor('#00000');

        $em->persist($session);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->query->get('id')} created.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/session/update", name="api_session_update", methods={"POST"})
     */
    public function updateSession(Request $request, NormalizerInterface $normalizer): Response
    {
        if (!($request->request->get('username') && $request->request->get('description') && $request->request->get('prix')&& $request->request->get('date_debut')&& $request->request->get('date_fin') ))
            return new Response(
                '{"error": "Missing username or coachname or description or price of date debut or date fin."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $session = $this->getDoctrine()->getRepository(Sessioncoaching::class)->findOneBy(["id" => $request->request->get('id')]);
        $session->setUser($this->getDoctrine()->getRepository(User::class)->findOneBy(["username"=>$request->request->get('username')]));
        $session->setDescription($request->request->get('description'));
        // date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', $request->request->get('date_debut')."0:0:0"));
        $session->setDateDebut(new \DateTime($request->request->get('date_debut')));
        $session->setDateFin(new \DateTime($request->request->get('date_fin')));
        $session->setPrix($request->request->get('prix'));
        $session->setBackgroundColor("#00000");
        $session->setBorderColor('#00000');
        $session->setTextColor('#00000');

        $em->persist($session);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->query->get('id')} created.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }


}
