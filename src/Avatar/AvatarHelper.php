<?php

namespace App\Avatar;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\HeaderUtils;

class AvatarHelper {

    // public const AVATAR_FOLDER = 'avatars';

    private $filesystem;

    public function __construct(Filesystem $filesystem, $avatarAbsolutePath){   

        $this->filesystem = $filesystem;

        //le param $avatarAbsolutePath est défini dans services.yaml dans services/defaults/bind et peut etre utilisé dans toutes les classes
        $this->avatarAbsolutePath = $avatarAbsolutePath;
    }

    public function saveSvg($svg){

        $filename = sha1(uniqid(mt_rand(), true)) . '.svg';
        $filepath = $this->avatarAbsolutePath . '/' . $filename;

        $this->filesystem->mkdir($this->avatarAbsolutePath);
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

    public function removeAvatarFolder(){
        
        $this->filesystem->remove($this->avatarAbsolutePath);
    }

}
