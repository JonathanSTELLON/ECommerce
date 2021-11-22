<?php

namespace App\Color;

class ColorGenerator{

    function getRandomColor(): string {
        $red = mt_rand(0,255);
        $green = mt_rand(0,255);
        $blue = mt_rand(0,255);

        return "rgb($red,$green,$blue)";
    }

    function getRandomColors(int $n): array {
        $colors = [];

        for ($i = 0; $i < $n; $i++) {
            $colors[] = $this->getRandomColor();
        }

        return $colors;
    }


}