<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\UpdateCategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/show", name="show")
     */
    public function index(CategoryRepository $repo): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $repo->findAll(),
            'user' => $this->getUser()
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteCategory(Category $category): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirect($this->generateUrl('categoryshow'));
    }
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $request->files->get('category_form')['image'];
            $filename = md5(uniqid()) . '.png';
            $file->move($this->getParameter('shop_image_directory'), $filename);
            $category->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $category->setIsEnabled(1);
            $em->persist($category);
            $em->flush();
            return $this->redirect($this->generateUrl('categoryshow'));
        }
        return $this->render('category/createCategory.html.twig', [
            'formCategory' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }
    /**
     * @Route("/update/{id}", name="update")
     */
    public function updateCategory(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $form = $this->createForm(UpdateCategoryType::class, $category);
        $img=$category->getImage();

        

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $request->files->get('update_category')['image'];
            if($file){
                $filename = md5(uniqid()) . '.png';
                $file->move($this->getParameter('shop_image_directory'), $filename);
                $category->setImage($filename);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirect($this->generateUrl('categoryshow'));
            }
            else{
                $category->setImage($img);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirect($this->generateUrl('categoryshow'));
            } 
        }
        return $this->render('category/updateCategory.html.twig', [
            'formCategory1' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }
    /**
     * @Route("/cancel", name="cancel")
     */
    public function cancel()
    {
        return $this->redirect($this->generateUrl('categoryshow'));
    }

    //front functions//

     /**
     * @Route("/show_front", name="show_front")
     */
    public function show_front(CategoryRepository $repo)
    {
        return $this->render('category/showCategory_front.html.twig', [
            'categories' => $repo->findAll(),
            'user' => $this->getUser()
        ]);
    }
}
