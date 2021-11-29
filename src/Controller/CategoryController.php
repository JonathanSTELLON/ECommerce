<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController{

    /**
     * @Route("/category/{slug}", name="category_show")
     */
    public function showCat(Category $category, Request $request, $slug):Response{

        return $this->render('category/index.html.twig', [
            'category' => $category,
            'slug' => $slug
        ]);

    }
}