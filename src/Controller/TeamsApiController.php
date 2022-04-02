<?php

namespace App\Controller;

use App\Entity\Matchs;
use App\Entity\Teams;
use App\Form\TeamsType;
use App\Form\TeamsBackType;
use App\Repository\MatchsRepository;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class TeamsApiController extends AbstractController
{

    /**
     * @Route("/api/teams/allteams", name="api_team", methods={"GET"})
     */
    public function allTeams(NormalizerInterface $normalizer): Response
    {
        $team = $this->getDoctrine()->getRepository(Teams::class)->findAll();

        $jsonContent = $normalizer->normalize($team, 'json', ['groups' => 'post:read']);
        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json','Content-Type' => 'application/json']);
    }

    /**
     * @Route("api/teams/addteam", name="api_add_team")
     * @throws ExceptionInterface
     */
    public function addteam(NormalizerInterface $normalizer,Request $request): Response
    {$em=$this->getDoctrine()->getManager();
        $team=new Teams();

        $team->setTeamName($request->get('TeamName'));
        $team->setGamersNb($request->get('gamersNb'));
        $team->setRank($request->get('rank'));
        $team->setImage($request->get('image'));
        $team->setVerified($request->get('verified'));


        $em->persist($team);
        $em->flush();
        $jsonContent=$normalizer->normalize($team,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("api/teams/updateteam/{id}", name="updateteam")
     */
    public function updateteam($id,Request $request,NormalizerInterface $normalizer)
    {$em=$this->getDoctrine()->getManager();
        $team=$em->getRepository(Teams::class)->find($id);
        $team->setTeamName($request->get('TeamName'));
        $team->setGamersNb(3);
        $team->setRank($request->get('rank'));
        $team->setVerified(1);

        $em->persist($team);
        $em->flush();
        $jsonContent=$normalizer->normalize($team,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("api/teams/delete", name="api_team_delete")
     */
    public function deleteTeam(MatchsRepository  $repository,Request $request,NormalizerInterface $normalizable)
    { $team = $this->getDoctrine()->getRepository(Teams::class)->findOneBy(['id' => $request->query->get('id')]);
        if ($team == null)
            return new Response(
                '{"error": "Team not found."}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->query->get('id')} deleted.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }


}