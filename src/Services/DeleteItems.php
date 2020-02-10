<?php
namespace App\Services;

class DeleteItems
{
    /**
     * Suprime un dossier avec tous les fichiers inclus dedans
     */
    public function deleteDirectoryAndHisFiles($path)
    {
        //Initialisation de la variable pour supprimer le dossier
        $eraseDir = true;
        if(is_dir($path)) {
            $objects = scandir($path);
            //dd($objects);
            foreach($objects as $object) {
                if($object != "." && $object != "..") {
                    if(is_file($path . $object)) {
                        unlink($path . $object);
                    }
                    //Si le dossier contient d'autre dossier, par sécurité, on ne le supprime plus
                    if(is_dir($path . $object)) {
                        $eraseDir = false;
                    }
                    
                }
            }
            if($eraseDir) rmdir($path);
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