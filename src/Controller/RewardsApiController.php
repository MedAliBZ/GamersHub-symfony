<?php

namespace App\Controller;

use App\Entity\Rewards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\File as FileFile;

class RewardsApiController extends AbstractController
{
    /**
     * @Route("/api/AllRewards", name="api_AllRewards")
     */

    public function AllRewards(NormalizerInterface $Normalizer): Response
    {
        $repository = $this->getDoctrine()->getRepository(Rewards::class);
        $blog = $repository->findAll();
        $jsonContent = $Normalizer->normalize($blog, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent),
        200,
        ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    
    }

    /**
     * @Route("/api/getRewardsById/{id}", name="api_getRewards")
     */

    public function getRewardsById(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Rewards::class);
        $p = $repository->find($id);
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/deleteRewards/{id}", name="api_deleteRewards")
     */

    public function delete(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Rewards::class);
        $p = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($p);
        $em->flush();
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response("blog deleted" . json_encode($jsonContent));
    }

    /**
     * @Route("/api/createRewards", name="api_createRewards")
     */
    public function create(Request $request, NormalizerInterface $Normalizer)
    {
        $blog = new Rewards();

        $em = $this->getDoctrine()->getManager();
       // $user = $em->getRepository(User::class)->find($request->get('id'));


        $blog->setType($request->get('type'));
        $blog->setQuantity($request->get('quantity'));
        
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
     * @Route("/api/updateRewards/{id}", name="api_updateRewards")
     */
    public function update(Request $request, NormalizerInterface $Normalizer, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository(Rewards::class)->find($id);
   
        $blog->setType($request->get('type'));
        $blog->setQuantity($request->get('quantity'));


        $em->flush();

        $jsonContent = $Normalizer->normalize($blog, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
