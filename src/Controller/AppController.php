<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('frontBase.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirect('login');
        }
        return $this->render('backBase.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

}
