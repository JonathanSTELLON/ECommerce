<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class AvatarExtension extends AbstractExtension{

    private $avatarAbsoluteUrl;

    public function __construct($avatarAbsoluteUrl){   

        $this->avatarAbsoluteUrl = $avatarAbsoluteUrl;
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

        $avatarPath = $this->avatarAbsoluteUrl . '/' . $filename;
        return $avatarPath;
    }
}
