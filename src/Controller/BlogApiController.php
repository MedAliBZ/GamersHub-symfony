<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\File as FileFile;

class BlogApiController extends AbstractController

{
    /**
     * @Route("/api/AllBlog", name="api_AllBlog")
     */

    public function AllBlog(NormalizerInterface $Normalizer): Response
    {
        $repository = $this->getDoctrine()->getRepository(Blog::class);
        $blog = $repository->findAll();
        $jsonContent = $Normalizer->normalize($blog, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent),
        200,
        ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    
    }

    /**
     * @Route("/api/getBlogById/{id}", name="api_getBlog")
     */

    public function getBlogById(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Blog::class);
        $p = $repository->find($id);
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/deleteBlog/{id}", name="api_deleteBlog")
     */

    public function delete(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Blog::class);
        $p = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($p);
        $em->flush();
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response("blog deleted" . json_encode($jsonContent));
    }

    /**
     * @Route("/api/createBlog", name="api_createBlog")
     */
    public function create(Request $request, NormalizerInterface $Normalizer)
    {
        $blog = new Blog();

        $em = $this->getDoctrine()->getManager();
       // $user = $em->getRepository(User::class)->find($request->get('id'));


        $blog->setTitle($request->get('Title'));
        $blog->setDescription($request->get('description'));
        $blog->setImage("02-6280f757229ac.png");
        
       /* $file = new FileFile($request->get('image'));


        $filename = md5(uniqid()) . '.'.$file->guessExtension();
        $file->move($this->getParameter('blog_image_directory'), $filename);


        $blog->setImage($filename);*/

        $em->persist($blog);
        $em->flush();

        $jsonContent = $Normalizer->normalize($blog, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent),
        200,
        ['Accept' => 'application/json',
        'Content-Type' => 'application/json']);
        
    }


    /**
     * @Route("/api/updateBlog/{id}", name="api_updateBlog")
     */
    public function update(Request $request, NormalizerInterface $Normalizer, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository(Blog::class)->find($id);
   
        $blog->setTitle($request->get('Title'));
        $blog->setDescription($request->get('description'));


        $em->flush();

        $jsonContent = $Normalizer->normalize($blog, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

}
