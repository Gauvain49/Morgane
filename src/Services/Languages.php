<?php
namespace App\Services;

use App\Entity\MgLanguages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Languages extends AbstractController
{
    /**
     * Récupère les langue active
     **/
    public function getLanguages()
    {
        $languages = $this->getDoctrine()->getRepository(MgLanguages::class)->findBy(['lang_active' => true]);
        return $languages;
    }

    /**
     * Récupère le language par défaut
     **/
    public function languageDefault()
    {
        $languageDefault = $this->getDoctrine()->getRepository(MgLanguages::class)->findOneBy(['lang_default' => true]);
        return $languageDefault ;
    }

}