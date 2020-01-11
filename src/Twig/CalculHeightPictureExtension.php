<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CalculHeightPictureExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('newHeight', [$this, 'calculHeight']),
        ];
    }

    public function calculHeight($height_origin, $width_origin, $width_redimension)
    {
        $ratio = $width_origin/$width_redimension;
        return round($height_origin/$ratio);
    }
}