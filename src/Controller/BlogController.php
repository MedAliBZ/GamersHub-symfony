<?php

namespace App\Controller;


use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\PlayerRepository;
use App\Entity\Player;
use App\Repository\CommentsRepository;
use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\BadWordsRepository;
use App\Entity\Spam;
use App\Repository\SpamRepository;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\FileException;
use mofodojodino\ProfanityFilter\Check;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog/", name="blog_index", methods={"GET" , "POST"})
     */
    public function index(Request $request, BlogRepository $blogRepository, EntityManagerInterface $entityManager, BadWordsRepository $badWordsRepository): Response
    {
        $repo =$this->getDoctrine()->getRepository(Blog::class);
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
// TODO: create a service for profanity filter.
            // Profanity filter for advice.
            // This will get all badwords from database.
            $post= $blog->getDescription();
            $badWords = $badWordsRepository->findAll();
    // dump($badWords);die;
            $checkInDB = new Check($badWords);
            $hasProfanityInDB = $checkInDB->hasProfanity($blog->getDescription());
            //dump($hasProfanityInDB);die;
  //        C
//            $checkNative = new Check();
 //           $hasProfanityInNative = $checkNative->hasProfanity($blog->getContent());
//            $hasProfanityInDB ;
//            $hasProfanityInNative = false;
            if ($hasProfanityInDB) {
                $postClean = $blogRepository->getAllPosts($post,$badWords);
               // dd($postClean);
                $blog->setDescription($postClean);
                //dump($badWords);die;
                //$this->addFlash('danger', "Your advice contains bad words that can hurt and offend the shoutee. Please be mindful to the feelings of your fellow shoutee.");
                // $this->get('session')->set('adv', $blog->getDescription());
                // return $this->redirectToRoute('blog_index', []);
            }
            
            
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        'img\blog', 
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $blog->setImage($newFilename);
            }

                $blog->setUser($this->getUser());
                date_default_timezone_set('Europe/Paris');
                $dateTime = date_create_immutable_from_format('d/m/Y H:i:s', date('d/m/Y H:i:s', time()));
                $blog->setpublishedAt($dateTime);

            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);}
        
     
        return $this->render('blog/blog.html.twig', [
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
     * @Route("/blog/post/{id}", name="Post", methods={"GET" , "POST"})
     */
    public function replyPost(BlogRepository $blogRepository, CommentsRepository $commentsRepository,$id ,Request $request, EntityManagerInterface $entityManager): Response
    {
        $blog=$this->getDoctrine()->getRepository(Blog::class)->find($id);
        $commentaire =new Comments();
        $commentaire->setBlog($blog);
        $commentaire->setUser($this->getUser());

        $blog=$commentaire->getBlog();
        $value = $blog->getViews();
        $value = $value + 1 ;
        $blog->setViews($value);
        $entityManager->flush();

        $form = $this->createForm(CommentsType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            date_default_timezone_set('Europe/Paris');
            $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
            $commentaire->setcommentedAt($dateTime);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('Post', ['id'=>$blog->getId()]);
        }
        return $this->render('comments/commentsFront.html.twig', [
            'blogList' => $blogRepository->find($id),
            'form' => $form->createView(),
            'blog' => $blog,
            'comment'=>$commentaire,
            'commentsList' => $commentsRepository->findAll(),
            'user' => $this->getUser()

        ]);
    }

     /**
     * @Route("/admin/blog", name="blog_show")
     */
    public function show(CommentsRepository $commentsRepository, BlogRepository $blogRepository, SpamRepository $spamRepository): Response
    {
        $blog = new Blog();
        $commentaire =new Comments();
        $repo =$this->getDoctrine()->getRepository(Blog::class);
        return $this->render('blog/backBlog.html.twig', [
            'user' => $this->getUser(),
            'blog' => $blog,
            'publicationList'=> $repo->findAll(),
            'commentsList' => $commentsRepository->findAll(),
            'spamList' => $spamRepository->findAll()
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
     * @Route("/admin/blog/delete/{id}", name="blog_delete", methods={"POST","GET"})
     */
    public function deleteBlog( Blog $blog): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Blog::class);
        return $this->redirectToRoute("blog_show");
    }

     /**
     * @Route("/admin/comment/edit/{id}", name="editComments", methods={"GET", "POST"})
     */
    public function editComment(Request $request, Comments $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set('Europe/Paris');
            $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
            $comment->setcommentedAt($dateTime);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('blog_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comments/commentsBackUpdate.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
            'user' => $this->getUser(),

        ]);
    }
   
    /**
     * @Route("/admin/comment/delete/{id}", name="deleteComment", methods={"POST","GET"})
     */
    public function deleteComment( Comments $comment): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(Comments::class);
        return $this->redirectToRoute("blog_show");
    }

      
}
