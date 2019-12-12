<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PathImageProductExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('pathProduct', [$this, 'pathProduct']),
        ];
    }

    /**
     * Retourne le chemin d'une image à partir de son id
     *
     * $val est l'ID de l'image servant à construire le chemin
     */
    public function pathProduct($val)
    {
        if (strlen($val) > 1) {
            $field = '';
            $field_vignette = str_split($val);
            foreach ($field_vignette as $dossier) {
                $field .= $dossier . '/';
            }
        } else {
            $field = $val . '/';
        }
        return $field;
    }
}