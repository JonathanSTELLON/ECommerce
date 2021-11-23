<?php

namespace App\Controller;

use App\Avatar\AvatarSvgFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ReloadAvatarFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends AbstractController{

    /**
     * @Route("/ajax/avatar/generate", name="ajax_avatar_generate")
     */
    public function generateAvatar(Request $request, AvatarSvgFactory $avatarSvgFactory):Response{

        // On récupère la taille et le nombre de couleurs du formulaire
        $form = $this->createForm(ReloadAvatarFormType::class);
        $form->handleRequest($request);
        $data = $form->getData();

        // Création d'un avatar
        $size = $data['size'];
        $color = $data['color'];
        $svg = $avatarSvgFactory->createRandomAvatar($size, $color);

        // Création d'un avatar
        // $size = $data['size'] ?? $request->request->get('size', AvatarSvgFactory::DEFAULT_SIZE);
        // $color = $data['color'] ?? $request->request->get('color', AvatarSvgFactory::DEFAULT_NB_COLORS);

        $svg = $data['svg'] ?? $avatarSvgFactory->createRandomAvatar($size,$color);

        // Construction de la réponse
        $response = new Response($svg);
        $response->headers->set('Content-Type', 'image/svg+xml');

        return $response;

    }

}