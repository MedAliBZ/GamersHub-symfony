<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Form\UpdateProductType;
use App\Repository\ProductsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 /**
     * @Route("/products", name="products")
     */

class ProductsController extends AbstractController
{
    /**
     * @Route("/show", name="show")
     */
    public function index(ProductsRepository $repo): Response
    { 
        return $this->render('products/index.html.twig', [
            'products' =>$repo->findAll() ,
            'user' => $this->getUser()
        ]);
    }
     /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteCategory(Products $product): Response
    { 
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirect($this->generateUrl('productsshow'));
    }
      /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $product = new Products();
        ///
        $form = $this->createForm(ProductsFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $product->setCreationDate(null);
            $product->setModificationDate(null);
            $product->setIsEnabled(1);
            $em->persist($product);
            $em->flush();
            return $this->redirect($this->generateUrl('productsshow'));
        }
        return $this->render('products/createProduct.html.twig', [
            'formProduct' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }
      /**
     * @Route("/update/{id}", name="update")
     */
    public function updateCategory(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Products::class)->find($id);
        $form = $this->createForm(UpdateProductType::class, $product);
       
           $date= $product->getCreationDate();
           $value=$product->getIsEnabled();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $product->setIsEnabled($value);
            $product->setCreationDate($date);
            $product->setModificationDate(null);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('productsshow'));
        }
        return $this->render('products/updateProduct.html.twig', [
            'formProduct1' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }
       /**
     * @Route("/cancel", name="cancel")
     */
    public function cancel()
    {
        return $this->redirect($this->generateUrl('productsshow'));
    }
}

