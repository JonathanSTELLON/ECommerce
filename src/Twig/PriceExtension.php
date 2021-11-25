<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PriceExtension extends AbstractExtension
{
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
            new TwigFunction('format_price', [$this, 'formatPrice'], [
                'is_safe' => ['html']
            ]),
        ];
    }

    public function formatPrice($price)
    {
        $priceToformat = $price/100;
        $formatedPrice = number_format($priceToformat, 2, ',', ' ');
        $priceArray = explode(',',$formatedPrice);
        return $priceArray[0].',<span class="Cents">'.$priceArray[1].'</span> €';
    }
}
