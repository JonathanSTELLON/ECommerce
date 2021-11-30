<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ThumbnailExtension extends AbstractExtension{

    private $picturesAbsoluteUrl;

    public function __construct($picturesAbsoluteUrl){

        $this->picturesAbsoluteUrl = $picturesAbsoluteUrl;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('thumbnail_asset', [$this, 'thumbnailAsset']),
        ];
    }

    public function thumbnailAsset($filename)
    {
        
        $thumbnailPath = $this->picturesAbsoluteUrl . '/' . $filename;
        return $thumbnailPath;
    }
}
