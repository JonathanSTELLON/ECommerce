<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Report;
use App\Entity\Review;
use DateTimeImmutable;
use App\Entity\Product;
use App\Form\ReviewType;
use App\Repository\ReportRepository;
use App\Repository\ReviewRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController{

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show(ProductRepository $productRepository, Request $request, $slug, EntityManagerInterface $manager, ReviewRepository $reviewRepository):Response{

        $product = $productRepository->findOneBy(array('slug' => $slug));
        $sameCats = $productRepository->findSameCat($product);
        
        //On crée une exception 404 si l'id du produit n'existe pas
        if(!$product){
            throw $this->createNotFoundException();
        }

        // On crée un objet Review qui contiendra l'éventuel nouvel avis si l'internaute remplit le formulaire et on ne l'injecte pas car les entités ne sont pas des services
        $review = new Review(); 

        // On récupère l'utilisateur connecté
        $currentUser = $this->getUser(); 

        if($currentUser){
            $review->setNickname($currentUser->getFullName());
        }
        
        // On crée le formulaire en lui associant notre objet $review
        $form = $this->createForm(ReviewType::class, $review);

        $createdAt = new DateTimeImmutable();

        // On transmet les données de la requête au formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $review contient déjà les 3 champs du formulaire : nickname, content et grade
            // on le complète avec la date du jour
            $review->setCreatedAt($createdAt);
            // et le produit associé
            $review->setProduct($product);
            // On associe l'utilisateur connecté
            $review->setUser($currentUser);
            
            // On persiste les données dans la base
            $manager->persist($review);
            $manager->flush();

            //Message flash enregistré en session
            $this->addFlash('success', 'Votre avis a bien été publié');

            //Redirection
            return $this->redirectToRoute('product_show', ['slug'=>$product->getSlug()]);
        }

        // $comments = $reviewRepository->findBy(array('product' => $product), array('createdAt' => 'DESC'));
        $comments = $reviewRepository->findValidReviews($product);
       
        return $this->render('product/index.html.twig', [
            'product' => $product,
            'ReviewType' => $form->createView(),
            'comments' => $comments,
            'sameCats' => $sameCats
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

        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur peut supprimer son signalement de l'avis... 
        if($user->canUnreport($review)){

            // On récupère le signalement associé à l'utilisateur connecté et à l'avis récupéré en paramètre
            $report = $reportRepository->findOneBy(array('user' => $user, 'review' => $review));

            // ... on le supprime !
            $review->removeReport($report);

            $manager->persist($report);
            $manager->flush();
    
            $this->addFlash('success', 'Avis designalé');
        }

        return $this->redirectToRoute('product_show', ['slug' => $slug]);
    
    }

    /**
     * @Route("/product/{slug}/review/{id}/remove", name="review_remove")
     * @IsGranted("DELETE_REVIEW", subject="review")
     */
    public function removeReview(Review $review, $slug, EntityManagerInterface $manager){

        $manager->remove($review);
        $manager->flush();
    
        $this->addFlash('success', 'Avis supprimé');
      
        return $this->redirectToRoute('product_show', ['slug' => $slug]);
    }

    /**
     * @Route("/product/{slug}/favorite", name="favorite")
     */
    public function favorite(Product $favorite, EntityManagerInterface $manager, $slug){

        $user = $this->getUser();

        if($user->canfavorite($favorite)){
            $user->addFavorite($favorite);

            $manager->persist($favorite);
            $manager->flush();
    
            $this->addFlash('success', 'Produit ajouté dans vos favoris');

        }
        else{
            $this->addFlash('failure', 'Vous avez déjà ajouté ce produit en favori');
        }
        return $this->redirectToRoute('product_show', ['slug' => $slug]);
    }

    /**
     * @Route("/product/{slug}/unfavorite", name="unfavorite")
     */
    public function unfavorite(Product $favorite, EntityManagerInterface $manager, $slug){

        $user = $this->getUser();

        if($user->canUnfavorite($favorite)){
            $user->removefavorite($favorite);

            $manager->persist($favorite);
            $manager->flush();

            $this->addFlash('success', 'Produit supprimé des favoris');
        }
        return $this->redirectToRoute('product_show', ['slug' => $slug]);

    }
}