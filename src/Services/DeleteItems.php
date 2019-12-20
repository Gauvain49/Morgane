<?php
namespace App\Services;

class DeleteItems
{
    /**
     * Suprime un dossier avec touts les fichiers inclus dedans
     */
    public function deleteDirectoryAndHisFiles($path)
    {
        if(is_dir($path)) {
            $objects = scandir($path);
            foreach($objects as $object) {
                if($object != "." && $object != "..") {
                    unlink($path . $object);
                }
            }
            rmdir($path);
        }
    }

    /**
     * Supprime un fichier numérique
     **/
    public function deleteNumerical($path, $filename)
    {
        //On récupère le chemin du dossier
        $pathFile = $path . '/' . $filename;
        if($path != "." && $path != "..") {
            unlink($pathFile);
            rmdir($path);
        }
    }
}