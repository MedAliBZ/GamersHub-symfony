<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\PlayerRepository;
use App\Entity\Player;


use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog/", name="blog_index", methods={"GET" , "POST"})
     */
    public function index(Request $request, BlogRepository $blogRepository, EntityManagerInterface $entityManager): Response
    {
        $repo =$this->getDoctrine()->getRepository(Blog::class);
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setUser($this->getUser());
            date_default_timezone_set('Europe/Paris');
            $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
            $blog->setpublishedAt($dateTime);
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
        }
     
        return $this->render('blog/blog.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'user' => $this->getUser(),
            'publicationList'=> $repo->findAll(),
            'blog' => $blog,
            'form' => $form->createView()
            
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setPlayer($this->getPlayer());
            date_default_timezone_set('Europe/Paris');
            $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
            $blog->setpublishedAt($dateTime);
            $entityManager->persist($blog);
            $entityManager->flush();


            return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'playerForm' => $form->createView(),
            'user'=> $this->getUser()
        ]);
    }

     /**
     * @Route("/admin/blog", name="blog_show")
     */
    public function show(): Response
    {
        $blog = new Blog();

        $repo =$this->getDoctrine()->getRepository(Blog::class);
        return $this->render('blog/backBlog.html.twig', [
            'user' => $this->getUser(),
            'blog' => $blog,
            'publicationList'=> $repo->findAll()
        
        ]);
    }

    /**
     * @Route("/admin/Blog/{id}/edit", name="blog_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        $repo =$this->getDoctrine()->getRepository(Blog::class);

        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set('Europe/Paris');
            $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
            $blog->setpublishedAt($dateTime);
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/backUpdate.html.twig', [
            'user' => $this->getUser(),
            'blog' => $blog,
            'form' => $form->createView(),
            'publicationList'=> $repo->findAll()

        ]);
        
    }

    /**
     * @Route("/admin/Blog/{id}", name="blog_delete", methods={"POST","GET"})
     */
    //change route
    public function delete( Blog $blog): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Blog::class);
        return $this->redirectToRoute("blog_show");
    }

   
}
