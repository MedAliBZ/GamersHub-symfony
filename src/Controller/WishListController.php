<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/wishList", name="wishList")
     */
class WishListController extends AbstractController
{
    /**
     * @Route("/show", name="show")
     */
    public function wishListFc(SessionInterface $session, ProductsRepository $repo): Response
    { 
        $wishList = $session->get('wishList', []);
        $wishListWithData = [];
       
        foreach ($wishList as $id => $number) {
            $product=$repo->find($id);
            if($product){
            $wishListWithData[] = [
                'product' => $product,
                'number' => $number

            ];}
        }
        
        return $this->render('wish_list/index.html.twig', [
            'wishListWithData'=> $wishListWithData,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function addRemoveWishList($id,SessionInterface $session): Response
    { 
        $wishList = $session->get('wishList', []);
       
        if (!empty($wishList[$id])) {
            unset($wishList[$id]);
        } else {
            $wishList[$id]=1;
        }
        $session->set('wishList', $wishList);

        //return $this->redirect($this->generateUrl('wishListshow', ['user' => $this->getUser()]));
        return $this->json(['message'=>'ce marche bien'],200);
    }
}
