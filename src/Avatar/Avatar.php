<?php

namespace App\Avatar;

class Avatar {

    private $size;
    private $colors;
    private $grid;

    public function __construct(int $size, array $colors){
        
        $this->size = $size;
        $this->colors = $colors;

        $this->createRandomGrid();
    }

    public function createRandomGrid(){

         // On crée le tableau qui contiendra la grille
        $grid = [];
        
        // On commence par créer des lignes... 
        for($rowIndex = 0; $rowIndex<$this->size; $rowIndex++){
            // ... avec dans chaque ligne un petit tableau ! 
            $grid[$rowIndex] = [];
            // Ensuite pour chaque colonne... 
            for($colIndex = 0; $colIndex<$this->size/2; $colIndex++){
                // On tire une couleur aléatoire
                $randomIndexColor = mt_rand(0, count($this->colors)-1);
                // On remplit la case courante (ligne $rowIndex, colonne $colIndex) avec cette couleur
                $grid[$rowIndex][$colIndex] = $this->colors[$randomIndexColor];

                // On applique la symétrie
                $grid[$rowIndex][$this->size - ($colIndex +1)] = $this->colors[$randomIndexColor];
              
            }
        }
        // On stocke notre grille dans la propriété grid 
        //dump($grid);
        $this->grid = $grid;
    }

    public function getSize(){
        return $this->size;
    }

    public function getColors(){
        return $this->colors;
    }

    public function getGrid(){
        return $this->grid;
    }
}