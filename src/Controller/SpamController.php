<?php

namespace App\Controller;
use App\Entity\Blog;
use App\Repository\BlogRepository;


use App\Entity\Spam;
use App\Form\SpamType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SpamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SpamController extends AbstractController
{
    /**
     * @Route("/spam/", name="app_spam_index", methods={"GET"})
     */
    public function index(SpamRepository $spamRepository): Response
    {
        return $this->render('spam/index.html.twig', [
            'spam' => $spamRepository->findAll(),
        ]);
    }

     /** 
      * @Route("/blog/spam/{id}", name="newSpam", methods={"GET", "POST"})
      */
      public function newSpam( $id, EntityManagerInterface $entityManager): Response
      {
         $repo =$this->getDoctrine()->getRepository(Blog::class);
          $blog=$this->getDoctrine()->getRepository(Blog::class)->find($id);
          $spam = new Spam();
          $spam->setPost($blog);
          $spam->setUser($this->getUser());
          $entityManager->persist($spam);
          $entityManager->flush();
 
              return $this->render('blog/blog.html.twig', [
                 'user' => $this->getUser(),
                 'publicationList'=> $repo->findAll()
                 
             ]);
      }

    /**
     * @Route("/spam/{id}", name="app_spam_show", methods={"GET"})
     */
    public function show(Spam $spam): Response
    {
        return $this->render('spam/show.html.twig', [
            'spam' => $spam,
        ]);
    }


    /**
     * @Route("/admin/spam/{id}", name="ApprovePost")
     */
    public function ApprovePost( Spam $spam): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($spam);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Spam::class);
        return $this->redirectToRoute("blog_show");
    }

    /**
     * @Route("/admin/BlogSpam/{id}", name="deletePostSpam")
     */
    public function deleteSpamBlog( Blog $blog): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Blog::class);
        return $this->redirectToRoute("blog_show");
    }
}
