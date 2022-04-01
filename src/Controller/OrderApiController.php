<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Products;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File as FileFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;


class OrderApiController extends AbstractController
{
  
    /**
     * @Route("/api/addToCart", name="api_addToCart")
     */
  
    public function create(OrderRepository $repoOrd, CartRepository $repoCart, Request $request, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $ordArray = $repoOrd->findByTotalPrice(0);

        if ($ordArray == NULL) {
            //make the order first
            $order = new Order();
            $order->setIsPaid(0);
            $order->setTotalprice(0);
            $em->persist($order);
            $em->flush();

            //do the cart 
            $repo = $em->getRepository(Products::class);
            $product = $repo->find($request->get('productId'));
            $cart = new Cart();
            $cart->setProduct($product);
            $cart->setQuantity(1);
            $cart->setMyOrder($order);

            $em->persist($cart);
            $em->flush();
        } else {
            //get the order again
            $repoOrd1 = $em->getRepository(Order::class);
            $order1 = $repoOrd1->find($ordArray[0]->getId());
            //do the cart 
            $repo = $em->getRepository(Products::class);
            $product = $repo->find($request->get('productId'));
            //adding the same product
            $carts = $repoCart->findByOrderID($order1->getId());

            $condition = 0;
            foreach ($carts as $item) {
           
                if ($item->getProduct()== $product) {
                    $condition = 1;
                    $idCart = $item->getId();
                }
            }
            if ($condition == 0) {
                $cart = new Cart();
                $cart->setProduct($product);
                $cart->setQuantity(1);
                $cart->setMyOrder($order1);
                $em->persist($cart);
                $em->flush();
                $jsonContent = $Normalizer->normalize($cart, 'json', ['groups' => 'post:read']);
                return new Response(json_encode($jsonContent));
            } else {
                $repoCart1 = $em->getRepository(Cart::class);
                $cartFound = $repoCart1->find($idCart);
                $quantity = $cartFound->getQuantity();
                $cartFound->setQuantity($quantity = $quantity + 1);
                $em->flush();
            }
        }

      return new Response("item added ");
        
    }



    /**
     * @Route("/api/AllCarts", name="api_AllCarts")
     */

    public function AllCarts(NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Cart::class);
        $carts = $repository->findAll();
        $cartsList=[];
        foreach($carts as $item)
        {   if($item->getMyOrder()->getTotalprice()==0)
              $cartsList[]=$item;
        }
        $jsonContent = $Normalizer->normalize($cartsList, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }
    
    /**
     * @Route("/api/updateOrder/{id}", name="api_updateOrder")
     */

    public function updateOrder(Request $request,NormalizerInterface $Normalizer,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);
        
        $order->setUser($em->getRepository(User::class)->find($request->get('userId')));
        $order->setTotalprice($request->get('totalprice'));
        $order->setIsCanceled(1);



        $em->flush();

        $jsonContent = $Normalizer->normalize($order, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
