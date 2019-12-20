<?php
namespace App\Services;

use App\Entity\MgParameters;
/*use App\Entity\MgParametersAddresses;
use App\Entity\MgPosts;*/
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Session\Session;

class AppService extends AbstractController
{
    /**
     * Récupère le nom du site pour l'afficher sur toutes les pages de l'admin
     **/
    public function getParams()
    {
        $parameters = $this->getDoctrine()->getRepository(MgParameters::class)->find(1);
        return $parameters;
    }

    /**
     * Donne une URL Gravatar ou une balise d'image complète pour une adresse électronique spécifiée.
     *
     * @param string $email l'adresse email de l'utilisater
     * @param string $s Taille en pixels, par défauts à 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    public function getGravatar( $email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * Retourne le nom d'un élément sécurisé en hash_hmac
     */
    public function getHashHmac($item)
    {
        return hash_hmac('sha256', $item, 'XB240061119133vc79', false);
    }
}