<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\UpdateCategoryType;
use App\Repository\CategoryRepository;
use DateTime;
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
            'categories' =>$repo->findAll() ,
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
            $em = $this->getDoctrine()->getManager();
            $category->setCreationDate(null);
            $category->setModificationDate(null);
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
       
           $date= $category->getCreationDate();
           $value=$category->getIsEnabled();
        
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
           
            $category->setIsEnabled($value);
            $category->setCreationDate($date);
            $category->setModificationDate(null);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('categoryshow'));
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
}
