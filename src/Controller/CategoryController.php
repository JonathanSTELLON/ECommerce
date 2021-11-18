<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Review;
use DateTimeImmutable;
use App\Entity\Category;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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