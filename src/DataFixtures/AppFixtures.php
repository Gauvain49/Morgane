<?php

namespace App\DataFixtures;

use App\Entity\MgCategories;
use App\Entity\MgLanguages;
use App\Entity\MgParameters;
use App\Entity\MgTaxes;
use App\Entity\MgTaxesLang;
use App\Entity\MgUsers;
use App\Services\TokenUtils;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}
    public function load(ObjectManager $manager)
    {
    	$tokenUtils = new TokenUtils();
        $LangDefault = '';

    	//Insertion d'un utilisateur
    	$user = new MgUsers();
    	$password = $this->encoder->encodePassword($user, 'Abcd1234');
    	$user->setUsername('gauvain49')
    		->setPassword($password)
    		->setLastname('Moreau')
    		->setFirstname('Grégory')
    		->setEmail('contact@percevalcreation.fr')
    		->setRoles(array('ROLE_ADMIN'))
    		->setDateAdd(new \DateTime())
    		->setActive(true)
    		->setToken($tokenUtils->generateToken(60));
        $manager->persist($user);

        //Insertion paramètres générales
        $parameters = new MgParameters();
        $parameters->setTitle('CMS Morgane')
        	->setSlogan('Simplifiez la gestion de votre site !')
        	->setSeoDescription('Outil de gestion de contenu site vitrine ou e-commerce.')
        	->setEmailContact('contact@percevalcreation.fr')
        	->setNbPosts(3);
        $manager->persist($parameters);

        //Insertion des langues
        $contentLanguage = array(
        	0 => array(
        		'Français',
        		'fr',
        		'fr-fr',
        		true,
        		true,
        		'fr.jpg'
        	),
        	2 => array(
        		'English',
        		'en',
        		'en-US',
        		false,
        		false,
        		'e.jpg'
        	)
        );
        foreach ($contentLanguage as $content) {
        	$languages = new MgLanguages();
	        $languages->setLangName($content[0])
	        	->setLangIsoCode($content[1])
	        	->setLangCode($content[2])
	        	->setLangDefault($content[3])
	        	->setLangActive($content[4])
	        	->setLangImg($content[5]);
	        	$manager->persist($languages);
                if ($content[0] == 'Français') {
                    $LangDefault = $languages;
                }
	    }

        //Insertion des Taxes
        $taxes = new MgTaxes();
        $taxesLang = new MgTaxesLang();
        $taxes->setTaxeRate(0)
            ->setTaxeActive(true);
        $manager->persist($taxes);
        $taxesLang->setTaxe($taxes)
            ->setLang($LangDefault)
            ->setTaxeName('Aucune Taxe');
        $manager->persist($taxesLang);

        $taxes = new MgTaxes();
        $taxesLang = new MgTaxesLang();
        $taxes->setTaxeRate(10)
            ->setTaxeActive(true);
        $manager->persist($taxes);
        $taxesLang->setTaxe($taxes)
            ->setLang($LangDefault)
            ->setTaxeName('TVA FR 10%');
        $manager->persist($taxesLang);

        $taxes = new MgTaxes();
        $taxesLang = new MgTaxesLang();        
        $taxes->setTaxeRate(20)
            ->setTaxeActive(true);
        $manager->persist($taxes);
        $taxesLang->setTaxe($taxes)
            ->setLang($LangDefault)
            ->setTaxeName('TVA FR 20%');
        $manager->persist($taxesLang);

        $taxes = new MgTaxes();
        $taxesLang = new MgTaxesLang();        
        $taxes->setTaxeRate(5.5)
            ->setTaxeActive(true);
        $manager->persist($taxes);
        $taxesLang->setTaxe($taxes)
            ->setLang($LangDefault)
            ->setTaxeName('TVA FR 5,5%');
        $manager->persist($taxesLang);

        //Insertion des catégories
        $cat = new MgCategories();
        $catLang = new MgCategoryLang();
        $cat->setParent(1)
            ->setActive(false)
            ->setForceActive(false)
            ->setType('root')
            ->setDateAdd(new \DateTime());
        $manager->persist($cat);
        $catLang->setCat($cat)
            ->setLang($LangDefault)
            ->setName('root')
            ->setSlug('root');
        $manager->persist($catLang);

        $manager->flush();
    }
}
