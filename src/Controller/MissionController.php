<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\MissionsDone;
use App\Entity\WishList;
use App\Form\MissionType;
use App\Form\MissionUpdateType;
use App\Repository\GameRepository;
use App\Repository\MissionRepository;
use App\Repository\WishListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MissionController extends AbstractController
{
    private function checkCondition($attribute, $operator, $variable): bool
    {
        $user = $this->getUser();
        $attributes = [
            'games' => count($user->getGames()),
            'wishlist' => count($this->getDoctrine()->getRepository(WishList::class)->findByUser($user))
        ];
        $result = "";

        eval("\$result = $attributes[$attribute] $operator $variable;");
        return $result;
    }

    /**
     * @Route("/missions", name="missions", methods={"GET"})
     */
    public function missionsFront(MissionRepository $missionRepository): Response
    {
        //all missions list
        $missionsList = $missionRepository->findAll();
        //missions done by the connected user (claimed and unclaimed)
        $missionsDone = $this->getUser()->getMissions();
        //unclaimed missions list
        $missionsUnclaimed = [];
        //remove missions done from missions list and fill missionsUnclaimed array
        foreach ($missionsDone as $missionDone) {
            if (!$missionDone->getIsClaimed()) {
                array_push($missionsUnclaimed, $missionDone);
            }
            unset($missionsList[array_search($missionDone->getMission(), $missionsList)]);
        }
        //check the undone missions and update them if they are done and create a new missions list
        $newMissionsList = [];
        foreach ($missionsList as $mission) {
            if ($this->checkCondition($mission->getAttribute(), $mission->getOperator(), $mission->getVariable())) {
                $newMissionDone = new MissionsDone();
                $newMissionDone->setIsClaimed(0)->setUser($this->getUser())->setMission($mission);
                array_push($missionsUnclaimed, $newMissionDone);
                $this->getDoctrine()->getManager()->persist($newMissionDone);
                $this->getDoctrine()->getManager()->flush();
            } else
                array_push($newMissionsList, $mission);
        }
//        dd($missionsUnclaimed);
        return $this->render('mission/index.html.twig', [
            'missionsList' => $newMissionsList,
            'missionsUnclaimed' => $missionsUnclaimed,
            'user' => $this->getUser()
        ]);
    }


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

    /**
     * @Route("/mission/claim/{id}", name="mission_claim")
     */
    public function claim(MissionsDone $missionsDone, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($missionsDone->getUser() == $user) {
            $user->setCoins($user->getCoins() + $missionsDone->getMission()->getPrize());
            $missionsDone->setIsClaimed(true);
            $entityManager->persist($user);
            $entityManager->persist($missionsDone);
            $entityManager->flush();
        }
        return $this->redirectToRoute('missions');
    }
}
