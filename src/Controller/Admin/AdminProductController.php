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
     * @Route("/admin/product/edit", name="admin_product_edit")
     */
    public function edit(){

    }

    /**
     * @Route("/admin/product/delete", name="admin_product_delete")
     */
    public function delete(){

    }
}