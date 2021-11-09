<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

    /**
     * @Route("/", name="home_index")
     */
    public function index(ProductRepository $productRepository):Response{

        $products = $productRepository->findBy([],['createdAt' => 'DESC']);
        //dd($products);

        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }
    
}