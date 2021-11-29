<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Review;
use DateTimeImmutable;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use App\Repository\ProductRepository;
use App\Repository\ReportRepository;
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

    /**
     * @Route("/product/{slug}/review/{id}/reports", name="review_report")
     */
    public function reports(Review $review, EntityManagerInterface $manager, $slug){

        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur n'a pas déjà signalé cet avis... il peut le faire
        if ($user->canReport($review)) {

            $report = new Report();
            $report->setUser($this->getUser());
            $report->setReview($review);
            $report->setCreatedAt(new DateTimeImmutable);
    
            $manager->persist($report);
            $manager->flush();
    
            $this->addFlash('success', 'Avis signalé');
        }
        else{
            // Sinon on l'avertit qu'il l'a déjà fait
            $this->addFlash('failure', 'Vous avez déjà signalé cet avis');
        }
        
        // On redirige vers la page produit
        return $this->redirectToRoute('product_show', ['slug' => $slug]);
    }

   /**
     * @Route("/product/{slug}/review/{id}/unreports", name="review_unreport")
     */
    public function unreports(Review $review, string $slug, EntityManagerInterface $manager, ReportRepository $reportRepository){

        $user = $this->getUser();

        if($user->canUnreport($review)){

            $report = $reportRepository->findOneBy(array('user' => $user, 'review' => $review));

            $review->removeReport($report);

            $manager->persist($report);
            $manager->flush();
    
            $this->addFlash('success', 'Avis designalé');
        }

        return $this->redirectToRoute('product_show', ['slug' => $slug]);
    
    }

    /**
     * @Route("/product/{slug}/review/{id}/remove", name="review_remove")
     */
    public function removeReview(){

    }
}