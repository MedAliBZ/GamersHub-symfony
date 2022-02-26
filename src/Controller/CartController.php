<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


        return $this->redirect($this->generateUrl('cartshow', ['user' => $this->getUser()]));
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


        return $this->redirect($this->generateUrl('cartshow', ['user' => $this->getUser()]));
    }

    /**
     * @Route("/update", name="update")
     */
    public function update(SessionInterface $session, ProductsRepository $repo, Request $req)
    {

        $result = $_GET['data'];

        $cart = $session->get('cart', []);
        $cartWithData = [];
        $i = 0;

        foreach ($cart as $id => $quantity) {
            $quantity = $result[$i];
            if ($quantity > 0) {
                $cart[$id] = $quantity;
                $cartWithData[] = [
                    'product' => $repo->find($id),
                    'quantity' => $quantity

                ];

                $i++;
            }
        }

        $total = 0;
        foreach ($cartWithData as $item) {
            if ($item['quantity'] > 0) {
                $totalItem = $item['product']->getPrice() * $item['quantity'];
                $total += $totalItem;
            }
        }

        $session->set('cart', $cart);


        return $this->render('cart/index.html.twig', [
            'items' =>  $cartWithData,
            'total' => $total,
            'user' => $this->getUser()
        ]);
    }
}
