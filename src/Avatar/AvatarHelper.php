<?php

namespace App\Avatar;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\HeaderUtils;

class AvatarHelper {

    public const AVATAR_FOLDER = 'avatars';

    private $filesystem;

    public function __construct(Filesystem $filesystem){   
        $this->filesystem = $filesystem;
    }

    public function saveSvg($svg){

        /*// Si le dossier avatars n'existe pas, on le crée 
        if(!file_exists('avatars')){
            mkdir('avatars', 0777, true);
        }

        // On génère un nom de fichier unique avec l'extension .svg
        // On enregistre le code SVG dans le fichier
        $fileName = uniqid('avatar', false);
        $file = fopen("avatars/" . $fileName . ".svg", "c");
        fwrite($file, $svg);

        return $fileName;*/

        $filename = sha1(uniqid(mt_rand(), true)) . '.svg';
        $filepath = self::AVATAR_FOLDER . '/' . $filename;

        $this->filesystem->mkdir(self::AVATAR_FOLDER);
        $this->filesystem->touch($filepath);
        $this->filesystem->appendToFile($filepath, $svg);

        return $filename;
    }

    public function downloadSvg(){
        // $file = $svg;

        // On modifie les entêtes nécessaires
        header('Content-Description: File Transfer');
        header('Content-Type: img/svg+xml');
        header('Content-Disposition: attachment; filename="avatar.svg"');

        // readfile($file);
    }

}
