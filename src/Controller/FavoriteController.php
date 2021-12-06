<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FavoriteController extends AbstractController{

    /**
     * @Route("/favorites/{id}", name="show_favorites")
     */
    public function showFavorites(ProductRepository $productRepository, $id):Response{
        $user = $this->getUser();

        $favProducts = $productRepository->findBy([],['createdAt' => 'DESC']);
        $favProducts = $user->getFavorites();

        // dd($favProducts);

        return $this->render('favorites/index.html.twig', [
            'favProducts' => $favProducts
        ]);

    }
}