<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Form\MissionUpdateType;
use App\Repository\GameRepository;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MissionController extends AbstractController
{
    /**
     * @Route("/admin/missions", name="missionsAdmin", methods={"GET"})
     */
    public function index(MissionRepository $missionRepository): Response
    {
        return $this->render('mission/showBack.html.twig', [
            'missionsList' => $missionRepository->findAll(),
            'user' => $this->getUser()
        ]);
    }

    

    /**
     * @Route("/admin/missions/new", name="mission_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $mission->getBadge();
            $fileName = md5(uniqid()) . '.png';
            $mission->setBadge($fileName);
            $entityManager->persist($mission);
            $entityManager->flush();
            $file->move($this->getParameter('badges_directory'), $fileName);

            return $this->redirectToRoute('missionsAdmin');
        }

        return $this->render('mission/new.html.twig', [
            'mission' => $mission,
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/mission/{id}/edit", name="mission_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        $oldMission = $mission;
        $form = $this->createForm(MissionUpdateType::class, $mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["badge"]->getData() != null) {
                if (is_file($this->getParameter('badges_directory') . '/' . $oldMission->getBadge())) {
                    unlink($this->getParameter('badges_directory') . '/' . $oldMission->getBadge());
                }
                $file = $form["badge"]->getData();
                $fileName = md5(uniqid()) . '.png';
                $mission->setBadge($fileName);
                $file->move($this->getParameter('badges_directory'), $fileName);
            }
            $entityManager->persist($mission);
            $entityManager->flush();

            return $this->redirectToRoute('missionsAdmin');
        }

        return $this->render('mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/admin/mission/{id}", name="mission_delete")
     */
    public function delete(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        if (is_file($this->getParameter('badges_directory') . '/' . $mission->getBadge())) {
            unlink($this->getParameter('badges_directory') . '/' . $mission->getBadge());
        }
        $entityManager->remove($mission);
        $entityManager->flush();
        return $this->redirectToRoute('missionsAdmin');
    }
}
