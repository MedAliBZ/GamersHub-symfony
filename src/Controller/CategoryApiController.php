<?php 
namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File as FileFile;
use Symfony\Component\HttpFoundation\Request;
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

     /**
     * @Route("/api/getCategoryById/{id}", name="api_getCategory")
     */

    public function getCategoryById(NormalizerInterface $Normalizer,$id)
    {
        $repository=$this->getDoctrine()->getRepository(Category::class);
        $c=$repository->find($id);
        $jsonContent=$Normalizer->normalize($c,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }

    
     /**
     * @Route("/api/deleteCategory/{id}", name="api_deleteCategory")
     */

    public function delete(NormalizerInterface $Normalizer,$id)
    {
        $repository=$this->getDoctrine()->getRepository(Category::class);
        $c=$repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($c);
        $em->flush();
        $jsonContent=$Normalizer->normalize($c,'json',['groups'=>'post:read']);

        return new Response("category deleted".json_encode($jsonContent));
    }

    /**
     * @Route("/api/createCategory", name="api_createCategories")
     */
    public function create(Request $request,NormalizerInterface $Normalizer)
    {  
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        
            $category->setNameCategory($request->get('nameCategory'));
            $category->setDescription($request->get('Description'));
             

            $file=new FileFile($request->get('image'));
            
  
            $filename = md5(uniqid()) . '.png';
            $file->move($this->getParameter('shop_image_directory'), $filename);
   

            $category->setImage($filename);
           
            $category->setIsEnabled(1);

            $em->persist($category);
            $em->flush();

            $jsonContent=$Normalizer->normalize($category,'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));
    }

    
    /**
     * @Route("/api/updateCategory/{id}", name="api_updateCategories")
     */
    public function update(Request $request,NormalizerInterface $Normalizer,$id)
    {  
        
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
            $category->setNameCategory($request->get('nameCategory'));
            $category->setDescription($request->get('Description'));
            $category->setImage($request->get('image'));  
            $category->setIsEnabled(1);
            
            $em->flush();

            $jsonContent=$Normalizer->normalize($category,'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));
    }

     

}
