<?php

namespace App\Controller;

use App\Entity\User;
use App\Avatar\Avatar;
use App\Form\UserType;
use DateTimeImmutable;
use App\Avatar\AvatarHelper;
use App\Avatar\AvatarSvgFactory;
use App\Form\ReloadAvatarFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController{

    private $hasher;

    /**
     * @Route("/signup", name="account_signup")
     */
    public function signup(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, AvatarSvgFactory $avatarSvgFactory, AvatarHelper $avatarHelper, SessionInterface $session):Response{

        $user = new User;
        $this->hasher = $hasher;

        $form = $this->createForm(UserType::class, $user);
        $createdAt = new DateTimeImmutable();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setCreatedAt($createdAt);
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($this->hasher->hashPassword($user, $plainPassword));

            // Sauvegarde de l'avatar et transmission du nom du fichier en BDD
            $svg = $request->request->get('svg');
            $fileName = $avatarHelper->saveSvg($svg);
            $session->set('avatar', array(
                'svg' => $svg
            ));

            $user->setAvatar($fileName);

            $manager->persist($user);
            $manager->flush();
    
            $this->addFlash('success', 'Votre compte a été correctement créé');
    
            return $this->redirectToRoute('home_index');
    
        }

        //Avatar généré aléatoirement

        $size = $data['size'] ?? $request->request->get('size', AvatarSvgFactory::DEFAULT_SIZE);
        $color = $data['color'] ?? $request->request->get('color', AvatarSvgFactory::DEFAULT_NB_COLORS);

        $svg = $data['svg'] ?? $avatarSvgFactory->createRandomAvatar($size,$color);

        //Formulaire pour regénérer l'avatar
        $avatarForm = $this->createForm(ReloadAvatarFormType::class, null, [
            'action' => $this->generateUrl('ajax_avatar_generate'),
        ]);

        return $this->render('account/signup.html.twig', [
            'UserType' => $form->createView(),
            'svg' => $svg,
            'avatarType' => $avatarForm->createView()
        ]);

    }
}