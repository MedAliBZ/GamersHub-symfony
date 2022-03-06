<?php 
namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CategoryApiController extends AbstractController{

    /**
     * @Route("/api/AllCategories", name="api_AllCategories")
     */

     public function AllCategories(NormalizerInterface $Normalizer)
     {
         $repository=$this->getDoctrine()->getRepository(Category::class);
         $categories=$repository->findAll();
         $jsonContent=$Normalizer->normalize($categories,'json',['groups'=>'post:read']);

         return new Response(json_encode($jsonContent));
     }

     

}
?>