<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\CoachRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route("/rating/{id}", name="rating")
     */

    public function index(Request $request,$id,CoachRepository $rep): Response
    {   $nb=0;
        $coach=$rep->find($id);
        $rating=new Rating();
        $form=$this->createForm(RatingType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {   $rating->setCoach($coach);
            $rating->setUser($this->getUser());
            $var=$form->getData();
            if($var['q1']=='yes_q1')
            {
              $nb=$nb+1;
            }
            if($var['q2']=='yes_q2')
            {
                $nb=$nb+1;
            }
            if($var['q3']=='yes_q3')
            {
                $nb=$nb+1;
            }
            if($var['q4']=='yes_q4')
            {
                $nb=$nb+1;
            }
            $rating->setRate($nb);
            $em=$this->getDoctrine()->getManager();
            $em->persist($rating);
            $em->flush();
            return $this->redirectToRoute('coach_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('rating/index.html.twig', [
            'user' => $this->getUser(),
            'formrating' => $form->createView(),
        ]);
    }
}
