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

        $filename = sha1(uniqid(mt_rand(), true)) . '.svg';
        $filepath = self::AVATAR_FOLDER . '/' . $filename;

        $this->filesystem->mkdir(self::AVATAR_FOLDER);
        $this->filesystem->touch($filepath);
        $this->filesystem->appendToFile($filepath, $svg);

        return $filename;
    }

    public function downloadSvg(){

        // On modifie les entêtes nécessaires
        header('Content-Description: File Transfer');
        header('Content-Type: img/svg+xml');
        header('Content-Disposition: attachment; filename="avatar.svg"');
    }

}
