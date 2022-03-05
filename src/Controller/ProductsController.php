<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Form\UpdateProductType;
use App\Repository\ProductsRepository;
use App\Repository\WishListRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
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
            'products' => $repo->findAll(),
            'user' => $this->getUser()
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Products $product): Response
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

        $form = $this->createForm(ProductsFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->get('products_form')['image'];
            $filenames = "";
            foreach ($files as $file) {
                $filename = md5(uniqid()) . '.png';
                $file->move($this->getParameter('shop_image_directory'), $filename);
                $filenames .= $filename . "*";
            }
            $product->setImage($filenames);


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
    public function updateCategory(Request $request, $id){
        
        $product = $this->getDoctrine()->getRepository(Products::class)->find($id);
        $form = $this->createForm(UpdateProductType::class, $product);
        $imgs=$product->getImage();
       
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $files = $request->files->get('update_product')['image'];
            if($files){
                $filenames = "";
                foreach ($files as $file) {
                    $filename = md5(uniqid()) . '.png';
                    $file->move($this->getParameter('shop_image_directory'), $filename);
                    $filenames .= $filename . "*";
                }
                $product->setImage($filenames);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirect($this->generateUrl('productsshow'));
    
            }
            else
            {
                $product->setImage($imgs);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirect($this->generateUrl('productsshow'));  
            }
            
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

    ///front functions
     
     
    /**
     * @Route("/showProducts/{category}", name="showProducts")
     */
    public function showProducts(ProductsRepository $repo,$category,WishListRepository $repo1):Response
    {
        $productList=[];
        foreach($repo1->findByUser($this->getUser()) as $wishListItem){
           $product= $wishListItem->getProduct()->getID();
            $productList[]=[
                'myProduct'=>$repo->find($product),
            ];
        }
    
        return $this->render('products/showProducts_front.html.twig', [
            'products' => $repo->findByCategory($category),
            'productList'=> $productList ,
            'user' => $this->getUser()
        ]);
        
    }

     /**
     * @Route("/detailProduct/{id}", name="detailProduct")
     */
    public function detailProduct(ProductsRepository $repo,$id):Response
    {
        return $this->render('products/detailProduct_front.html.twig', [
            'product' => $repo->find($id),
            'user' => $this->getUser()
        ]);
        
    }

}
