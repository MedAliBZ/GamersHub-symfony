<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File as FileFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;


class ProductApiController extends AbstractController
{

    /**
     * @Route("/api/AllProducts", name="api_AllProducts")
     */

    public function AllProducts(NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Products::class);
        $products = $repository->findAll();
        $jsonContent = $Normalizer->normalize($products, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/getProductById/{id}", name="api_getProduct")
     */

    public function getProductById(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Products::class);
        $p = $repository->find($id);
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/deleteProduct/{id}", name="api_deleteProduct")
     */

    public function delete(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Products::class);
        $p = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($p);
        $em->flush();
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response("product deleted" . json_encode($jsonContent));
    }

    /**
     * @Route("/api/createProduct", name="api_createProduct")
     */
    public function create(Request $request, NormalizerInterface $Normalizer)
    {
        $product = new Products();

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($request->get('categoryId'));


        $product->setNameProduct($request->get('nameProduct'));
        $product->setDescription($request->get('description'));
        $product->setPrice($request->get('price'));
        $product->setCategory($category);
        $product->setQuantityStocked($request->get('quantityStocked'));


        $file = new FileFile($request->get('image'));


        $filename = md5(uniqid()) . '.png';
        $file->move($this->getParameter('shop_image_directory'), $filename);


        $product->setImage($filename);

        $product->setIsEnabled(1);

        $em->persist($product);
        $em->flush();

        $jsonContent = $Normalizer->normalize($product, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/updateProduct/{id}", name="api_updateProduct")
     */
    public function update(Request $request, NormalizerInterface $Normalizer, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Products::class)->find($id);
   
        $product->setNameProduct($request->get('nameProduct'));
        $product->setDescription($request->get('description'));
        $product->setPrice($request->get('price'));
        $product->setQuantityStocked($request->get('quantityStocked'));


        $em->flush();

        $jsonContent = $Normalizer->normalize($product, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }



    // /**
    //  * @Route("/api/showProducts/{id}", name="api_showProducts")
    //  */
    // public function showProducts(NormalizerInterface $Normalizer, $id): Response
    // {
    //     $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
    //     $repository = $this->getDoctrine()->getRepository(Products::class);

    //     $products =  $repository->findByCategory($category);;

    //     $jsonContent = $Normalizer->normalize($products, 'json', ['groups' => 'post:read']);

    //     return new Response(json_encode($jsonContent));
    // }
}
