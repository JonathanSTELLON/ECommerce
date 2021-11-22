<?php

namespace App\Avatar;

use App\Color\ColorGenerator;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AvatarSvgFactory{

    public const DEFAULT_SIZE = 8;
    public const DEFAULT_NB_COLORS = 4; 

    private $colorGenerator;
    private $twig;

    public function __construct(ColorGenerator $colorGenerator, Environment $twig){
        
        $this->twig = $twig;
        $this->colorGenerator = $colorGenerator;
    }

    public function createRandomAvatar(int $size, int $nbColors){

        $colors = $this->colorGenerator->getRandomColors($nbColors);
        $avatar = new Avatar($size, $colors);

        $svg = $this->twig->render('avatar/avatar.svg.twig', [
            'avatar' => $avatar
        ]);
        return $svg;

    }

}