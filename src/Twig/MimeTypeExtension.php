<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MimeTypeExtension extends AbstractExtension
{
    protected $mimesType = ['image/gif' => '.gif',
                            'image/pjpeg' => '.jpg',
                            'image/jpeg' => '.jpg',
                            'image/JPG' => '.jpg',
                            'image/X-PNG' => '.png',
                            'image/PNG' => '.png',
                            'image/png' => '.png',
                            'image/x-png' => '.png'];

    public function getFilters()
    {
        return [
            new TwigFilter('mimeType', [$this, 'mimeType']),
        ];
    }

    /**
     * Retourne le chemin d'une image à partir de son id
     *
     * $val est l'ID de l'image servant à construire le chemin
     */
    public function mimeType($mimeType)
    {
        if (!array_key_exists($mimeType, $this->mimesType)) {
            throw new \InvalidArgumentException(
                sprintf('Ce type_mime est inconnu : %s.', $mimeType)
            );
        }
        return $this->mimesType[$mimeType];
    }
}