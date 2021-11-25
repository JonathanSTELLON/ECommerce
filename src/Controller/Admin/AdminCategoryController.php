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
            $this->addFlash('success', 'Votre produit a été correctement modifié');

            return $this->redirectToRoute('admin_index');
            
        }

        return $this->render('admin/category/add.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);

    }

}