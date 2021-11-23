<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Avatar\AvatarHelper;
use Twig\Extension\AbstractExtension;

class AvatarExtension extends AbstractExtension{

    private $user;
    private $avatarDir;

    public function __construct($avatarDir){   

        $this->avatarDir = $avatarDir;
    }

    public function getFilters(): array{

        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array{

        return [
            new TwigFunction('avatar_asset', [$this, 'avatarAsset']),
        ];
    }

    public function avatarAsset($filename){

        $avatarPath = $this->avatarDir . '/' . $filename;
        return $avatarPath;
    }
}
