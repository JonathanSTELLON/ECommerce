<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProductController extends AbstractController{

    private $slugger;

    public function __construct(SluggerInterface $slugger){

        $this->slugger = $slugger;
    }
    
    /**
     * @Route("/admin/product/add", name="admin_product_add")
     */
    public function add(Request $request, EntityManagerInterface $manager):Response{

        $product = new Product();

        $productForm = $this->createForm(ProductType::class, $product);
        $createdAt = new DateTimeImmutable();

        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){

            $product->setCreatedAt($createdAt);
            $product->setSlug($this->slugger->slug($product->getName()));

            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', 'Votre produit a été correctement ajouté');
    
            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/product/add.html.twig', [
            'productForm' => $productForm->createView(),
        ]);

    }

    /**
     * @Route("/admin/product/edit/{id}", name="admin_product_edit")
     */
    public function edit(Request $request, Product $product = null, EntityManagerInterface $manager):Response{

        //On crée une exception 404 si l'id du produit n'existe pas
        if(!$product){
            throw $this->createNotFoundException();
        }

        $productForm = $this->createForm(ProductType::class, $product);
        $updatedAt = new DateTimeImmutable();

        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){

            $product->setUpdatedAt($updatedAt);

            $manager->flush();
            $this->addFlash('success', 'Votre produit a été correctement modifié');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'productForm' => $productForm->createView(),
        ]);

    }

    /**
     * @Route("/admin/product/delete/{id}", name="admin_product_delete")
     */
    public function delete(Request $request, $id, Product $product = null, EntityManagerInterface $manager){

        //On crée une exception 404 si l'id du produit n'existe pas
        if(!$product){
            throw $this->createNotFoundException();
        }

        $manager->getRepository(Product::class)->find($id);
        $manager->remove($product);
        $manager->flush();

        if ($request->isXmlHttpRequest()){
            return $this->json($id);
        }

        else{
            $this->addFlash('success', 'Votre produit a été supprimé');
            return $this->redirectToRoute('admin_index');
        }
    }

}