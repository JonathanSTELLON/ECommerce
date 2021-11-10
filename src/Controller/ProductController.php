<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController{

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show(ProductRepository $productRepository, Request $request, $slug):Response{

        $review = new Review(); //on ne l'injecte pas car les entités ne sont pas services
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            // dd($review);
        }

        $product = $productRepository->findOneBy(array('slug' => $slug));

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'ReviewType' => $form->createView()
        ]);

    }
}