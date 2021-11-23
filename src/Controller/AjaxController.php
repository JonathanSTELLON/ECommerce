<?php

namespace App\Controller;

use App\Avatar\AvatarSvgFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends AbstractController{

    /**
     * @Route("/ajax/avatar/generate", name="ajax_avatar_generate")
     */
    public function generateAvatar(Request $request, AvatarSvgFactory $avatarSvgFactory):Response{

        $size = $data['size'] ?? $request->request->get('size', AvatarSvgFactory::DEFAULT_SIZE);
        $color = $data['color'] ?? $request->request->get('color', AvatarSvgFactory::DEFAULT_NB_COLORS);

        $svg = $data['svg'] ?? $avatarSvgFactory->createRandomAvatar($size,$color);

        return new Response($svg);

    }

}