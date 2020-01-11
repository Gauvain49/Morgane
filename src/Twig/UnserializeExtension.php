<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UnserializeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('unserialize', [$this, 'unserialize']),
        ];
    }

    public function unserialize($content)
    {
        return unserialize($content);
    }
}