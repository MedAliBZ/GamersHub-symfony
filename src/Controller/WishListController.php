<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\WishList;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
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
        $WishList = $wishlistrepo->findByUser($this->getUser());
        $productList = [];

        foreach ($WishList as $item) {
          
            
                $productList[] = [
                    'product' => $item->getProduct(),
                ];
            
        }
   


        return $this->render('wish_list/index.html.twig', [
            'user' => $this->getUser(),
            'productList'=>$productList

        ]);
    }

    /**
     * @Route("/add/{id}/{userID}", name="add")
     */
    public function addRemoveWishList($id,$userID,UserRepository $userRepo, ProductsRepository $repo,WishListRepository $repoWishList): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $repo->find($id);
       
     
        if($repoWishList->findByProduct($product))
        {  
           
            $wishList= $repoWishList->findByProduct($product) ;
            $em->remove($wishList);
            $em->flush();
        }
        else{
           
            $wishList = new WishList();
            $wishList->setUser($userRepo->find($userID));
            $wishList->setProduct($repo->find($id));
            $em->persist($wishList);
            $em->flush();
      
        }

       //return $this->redirect($this->generateUrl('wishListshow', ['user' => $this->getUser()]));
        return $this->json(['message'=>'ce marche bien'],200);
    }
}
