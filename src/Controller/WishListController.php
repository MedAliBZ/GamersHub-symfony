<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\WishList;
use App\Repository\ProductsRepository;
use App\Repository\WishListRepository;
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
    public function wishListFc(WishListRepository $wishlistrepo, ProductsRepository $repo): Response
    {
        $WishList = $wishlistrepo->findAll($this->getUser());
        $productList = [];

        foreach ($WishList as $item) {
          
            foreach ($item->getProduct() as $item2) {
                $productList[] = [
                    'product' => $repo->find($item2->getId()),
                ];
            }
        }
   


        return $this->render('wish_list/index.html.twig', [
            'user' => $this->getUser(),
            'productList'=>$productList

        ]);
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function addRemoveWishList($id, ProductsRepository $repo,WishListRepository $repoWishList): Response
    {
        $em = $this->getDoctrine()->getManager();
        // $product = $repo->find($id);
        // if($product->getWishList()!= null)
        // {   
        //     $wishList=$repoWishList->find($product->getWishList());
        //     $em->remove($wishList);
        //     $em->flush();
        // }
        // else{
        // $wishList = new WishList();
        // $wishList->setUser($this->getUser());
        // $wishList->addProduct($product);
       
        // $em->persist($wishList);
        // $em->flush();
        // }

       // return $this->redirect($this->generateUrl('wishListshow', ['user' => $this->getUser()]));
        return $this->json(['message'=>'ce marche bien'],200);
    }
}
