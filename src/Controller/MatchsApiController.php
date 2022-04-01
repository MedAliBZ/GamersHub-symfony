<?php

namespace App\Controller;

use App\Entity\Matchs;
use App\Form\MatchsType;
use App\Repository\MatchsRepository;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



class MatchsApiController extends AbstractController
{

    /**
     * @Route("/api/matchs/allmatchs", name="api_match", methods={"GET"})
     */
    public function allMatchs(NormalizerInterface $normalizer): Response
    {
        $match = $this->getDoctrine()->getRepository(Matchs::class)->findAll();
        $jsonContent = $normalizer->normalize($match, 'json', ['groups' => 'post:read']);
        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
    }

    /**
     * @Route("api/matchs/addmatch", name="api_add_match")
     * @throws ExceptionInterface
     */
    public function addmatch(NormalizerInterface $normalizer,Request $request): Response
    {$em=$this->getDoctrine()->getManager();
        $match=new Matchs();

        $match->setMatchName($request->get('MatchName'));

        $match->setResult($request->get('result'));



        $em->persist($match);
        $em->flush();
        $jsonContent=$normalizer->normalize($match,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("/api/matchs/updatematch", name="updatematch")
     */
    public function updatematch($id,Request $request,NormalizerInterface $normalizer)
    {$em=$this->getDoctrine()->getManager();
        $match=$em->getRepository(Matchs::class)->find($id);
        $match->setMatchName($request->get('MatchName'));

        $match->setResult($request->get('result'));


        $em->persist($match);
        $em->flush();
        $jsonContent=$normalizer->normalize($match,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/api/matchs/match/delete", name="api_match_delete")
     */
    public function deleteMatch(MatchsRepository  $repository,Request $request,NormalizerInterface $normalizable)
    { $match = $this->getDoctrine()->getRepository(Matchs::class)->findOneBy(['id' => $request->query->get('id')]);
        if ($match == null)
            return new Response(
                '{"error": "Match not found."}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($match);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->query->get('id')} deleted.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }

}
