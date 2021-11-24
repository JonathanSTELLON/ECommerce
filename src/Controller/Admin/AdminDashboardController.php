<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController{

    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(ProductRepository $productRepository):Response{

        $products = $productRepository->findBy([],['createdAt' => 'DESC']);

        return $this->render('admin/dashboard/index.html.twig', [
            'products' => $products
        ]);
    }

}
