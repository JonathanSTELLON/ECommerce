<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategoryController extends AbstractController{

    private $slugger;

    public function __construct(SluggerInterface $slugger){

        $this->slugger = $slugger;
    }

    /**
     * @Route("/admin/category/add", name="admin_category_add")
     */
    public function add(Request $request, EntityManagerInterface $manager):Response{

        $category = new Category;

        $categoryForm = $this->createForm(CategoryType::class, $category);
        $createdAt = new DateTimeImmutable();

        $categoryForm->handleRequest($request);

        if($categoryForm->isSubmitted() && $categoryForm->isValid()){

            $category->setCreatedAt($createdAt);
            $category->setSlug($this->slugger->slug($category->getName()));

            $manager->persist($category);
            $manager->flush();
            $this->addFlash('success', 'Votre catégorie a été correctement ajoutée');

            return $this->redirectToRoute('admin_index');
            
        }

        return $this->render('admin/category/add.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);

    }

    /**
     * @Route("/admin/category/edit/{id}", name="admin_category_edit")
     */
    public function edit(Request $request, Category $category = null, EntityManagerInterface $manager):Response{

        if(!$category){
            throw $this->createNotFoundException();
        }

        $categoryForm = $this->createForm(CategoryType::class, $category);
        $updatedAt = new DateTimeImmutable();

        $categoryForm->handleRequest($request);

        if($categoryForm->isSubmitted() && $categoryForm->isValid()){

            $category->setUptadedAt($updatedAt);

            $manager->flush();
            $this->addFlash('success', 'Votre catagorie a été correctement modifiée');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'categoryForm' => $categoryForm->createView(),
        ]);

    }

    /**
     * @Route("/admin/category/delete/{id}", name="admin_category_delete")
     */
    public function delete(Request $request, $id, Category $category = null, EntityManagerInterface $manager){

        if(!$category){
            throw $this->createNotFoundException();
        }

        $manager->getRepository(Category::class)->find($id);
        $manager->remove($category);
        $manager->flush();

        if($request->isXmlHttpRequest()){
            return $this->json($id);
        }

        else{
            $this->addFlash('success', 'Votre catégorie a été supprimée');
            return $this->redirectToRoute('admin_index');
        }
    }
}