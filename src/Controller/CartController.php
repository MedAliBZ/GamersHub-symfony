<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/show", name="show")
     */
    public function index(SessionInterface $session, ProductsRepository $repo): Response
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $repo->find($id),
                'quantity' => $quantity

            ];
        }
       

        $total = 0;
        foreach ($cartWithData as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }


        return $this->render('cart/index.html.twig', [
            'items' =>  $cartWithData,
            'total' => $total,
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(SessionInterface $session, $id, ProductsRepository $repo)
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);
        $category = $repo->find($id)->getCategory();
        $idCat = $category->getId();

        return $this->redirect($this->generateUrl('productsshowProducts', [
            'category' => $idCat,
            'user' => $this->getUser()
        ]));
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(SessionInterface $session, $id)
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);


        return $this->redirect($this->generateUrl('cartshow'));
    }
    /**
     * @Route("/minus/{id}", name="minus")
     */
    public function minus(SessionInterface $session, $id)
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])&& $cart[$id]!=1 ) {
            $cart[$id]--;
        } else {
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);

        return $this->redirect($this->generateUrl('cartshow'));
    }
       /**
     * @Route("/plus/{id}", name="plus")
     */
    public function plus(SessionInterface $session, $id)
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);

        return $this->redirect($this->generateUrl('cartshow'));
    }
}
