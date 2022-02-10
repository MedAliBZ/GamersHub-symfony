<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirect('app_login');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }
}
