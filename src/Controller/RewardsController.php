<?php

namespace App\Controller;

use App\Entity\Rewards;
use App\Form\RewardsType;
use App\Repository\RewardsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RewardsController extends AbstractController{
    /**
     * @Route("/", name="rewards_index", methods={"GET"})
     */
    // public function index(RewardsRepository $rewardsRepository): Response
    // {
        // return $this->render('rewards/index.html.twig', [
            // 'rewards' => $rewardsRepository->findAll(),
        // ]);
  

    /**
     * @Route("/admin/rewards/new", name="rewards_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, RewardsRepository $rewardsRepository): Response
    {
        $reward = new Rewards();
        $form = $this->createForm(RewardsType::class, $reward);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reward);
            $entityManager->flush();

            return $this->redirectToRoute('rewards_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rewards/backRewards.html.twig', [
            'reward' => $reward,
            'rewards' => $rewardsRepository->findAll(),
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }
   
    /**
     * @Route("/admin/rewards", name="backRewards")
     */
    public function back(Request $request, Rewards $reward, EntityManagerInterface $entityManager): Response
    {
        $reward = new Rewards();
        $form = $this->createForm(RewardsType::class, $reward);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reward);
            $entityManager->flush();

            return $this->redirectToRoute('backRewards', [], Response::HTTP_SEE_OTHER);
    }
}

    /**
     * @Route("/admin/rewards/{id}/edit", name="rewards_edit")
     */
    public function edit(Request $request, Rewards $reward, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RewardsType::class, $reward);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('rewards_new');
        }

        return $this->render('rewards/rewardsUpdate.html.twig', [
            'user' => $this->getUser(),
            'reward' => $reward,
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/admin/rewards/{id}", name="rewards_delete")
     */
    public function delete(Rewards $reward): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($reward);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Rewards::class);
        return $this->redirectToRoute("rewards_new");
    }

     /**
     * @Route("tournaments/rewards/show", name="rewards_show")
     */
    public function show(): Response
    {
        $rewards = new Rewards();
        $repo =$this->getDoctrine()->getRepository(Rewards::class);
        return $this->render('rewards/rewards.html.twig', [
            'rewards' => $rewards,
            'user' => $this->getUser(),
            'rewardsList' => $repo->findAll(),

        ]);
    }

}
