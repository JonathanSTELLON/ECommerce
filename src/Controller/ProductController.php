<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController{

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show(ProductRepository $productRepository, Request $request, $slug, EntityManagerInterface $manager, ReviewRepository $reviewRepository):Response{

        $product = $productRepository->findOneBy(array('slug' => $slug));
        
        //On crée une exception 404 si l'id du produit n'existe pas
        if(!$product){
            throw $this->createNotFoundException();
        }

        $review = new Review(); //on ne l'injecte pas car les entités ne sont pas des services

        $currentUser = $this->getUser(); // On récupère l'utilisateur connecté

        if($currentUser){
            $review->setNickname($currentUser->getFullName());
        }
        
        $form = $this->createForm(ReviewType::class, $review);

        $createdAt = new DateTimeImmutable();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $review->setCreatedAt($createdAt);
            $review->setProduct($product);
            $review->setUser($currentUser);
            

            // On persiste les données dans la base
            $manager->persist($review);
            $manager->flush();

            //Message flash enregistré en session
            $this->addFlash('success', 'Votre avis a bien été publié');

            //Redirection
            return $this->redirectToRoute('product_show', ['slug'=>$product->getSlug()]);
        }

        $comments = $reviewRepository->findBy(array('product' => $product), array('createdAt' => 'DESC'));
        //dd($comments);

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'ReviewType' => $form->createView(),
            'comments' => $comments
        ]);
    }
}