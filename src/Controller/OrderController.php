<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order", name="order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/show/{id}", name="show")
     */
    public function index(OrderRepository $repo, $id, SessionInterface $session, ProductsRepository $repo1): Response
    {

        $order = $repo->find($id);
        $myCart = $session->get('cart', []);

        foreach ($myCart as $id => $quantity) {
            $productCart[] = [
                'product' => $repo1->find($id),
                'quantity' => $quantity
            ];
        }

        return $this->render('order/index.html.twig', [
            'order' => $order,
            'productTab' => $productCart,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(SessionInterface $session, ProductsRepository $repo)
    {
        $order = new Order();

        $myCart = $session->get('cart', []);
        $em = $this->getDoctrine()->getManager();


        $total = 0;
        foreach ($myCart as $id => $quantity) {

            $cart = new Cart();
            $cart->setProduct($repo->find($id));
            $cart->setQuantity($quantity);

            $totalItem = $repo->find($id)->getPrice() * $quantity;
            $total += $totalItem;

            $em->persist($cart);
            $order->addCart($cart);
        }



        $order->setIsCanceled(0);
        $order->setTotalPrice($total);
        $order->setUser($this->getUser());
        $em->persist($order);
        $em->flush();
        $id = $order->getId();

        return $this->redirect($this->generateUrl('ordershow', [
            'id' => $id,
            'user' => $this->getUser()
        ]));
    }

    
    /**
     * @Route("/cancel/{id}", name="cancel")
     */
    public function cancel($id,OrderRepository $orderRepo)
    {
        $order = $orderRepo->find($id);
        $em = $this->getDoctrine()->getManager();
        $order->setIsCanceled(1);
        $em->flush();
      

        return $this->redirect($this->generateUrl('cartshow',['user' => $this->getUser()]));
    }

    /**
     * @Route("/showBack", name="showBack")
     */
    public function showBack(OrderRepository $repo): Response
    {


        return $this->render('order/showBack.html.twig', [
            'orders' => $repo->findAll(),
            'user' => $this->getUser(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Order $order): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        return $this->redirect($this->generateUrl('ordershowBack',['user' => $this->getUser()]));
    }
}
