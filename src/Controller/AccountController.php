<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Avatar\Avatar;
use App\Avatar\AvatarHelper;
use App\Avatar\AvatarSvgFactory;
use App\Form\AvatarFormType;

class AccountController extends AbstractController{

    private $hasher;

    /**
     * @Route("/signup", name="account_signup")
     */
    public function signup(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, AvatarSvgFactory $avatarSvgFactory):Response{

        $user = new User;
        $this->hasher = $hasher;

        $form = $this->createForm(UserType::class, $user);
        $createdAt = new DateTimeImmutable();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setCreatedAt($createdAt);
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($this->hasher->hashPassword($user, $plainPassword));

            $manager->persist($user);
            $manager->flush();
    
            $this->addFlash('success', 'Votre compte a été correctement créé');
    
            return $this->redirectToRoute('home_index');
    
        }

        //AVATAR GENERE RANDOM

        $size = $data['size'] ?? $request->request->get('size', AvatarSvgFactory::DEFAULT_SIZE);
        $color = $data['color'] ?? $request->request->get('color', AvatarSvgFactory::DEFAULT_NB_COLORS);

        $svg = $data['svg'] ?? $avatarSvgFactory->createRandomAvatar($size,$color);

        return $this->render('account/signup.html.twig', [
            'UserType' => $form->createView(),
            'svg' => $svg,
            'size' => $size,
            'color' => $color
        ]);

    }
}