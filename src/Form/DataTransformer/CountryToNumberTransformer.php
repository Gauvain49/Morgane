<?php
// src/Form/DataTransformer/IssueToNumberTransformer.php
namespace App\Form\DataTransformer;

use App\Entity\MgCountries;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CountryToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (country) to a string (number).
     *
     * @param  Issue|null $country
     * @return string
     */
    public function transform($country)
    {
        if (null === $country) {
            return '';
        }

        return $country->getId();
    }

    /**
     * Transforms a string (number) to an object (country).
     *
     * @param  string $countryNumber
     * @return MgCountries|null
     * @throws TransformationFailedException if object (country) is not found.
     */
    public function reverseTransform($countryNumber)
    {
        // no issue number? It's optional, so that's ok
        if (!$countryNumber) {
            return;
        }

        $country = $this->entityManager
            ->getRepository(MgCountries::class)
            // query for the country with this id
            ->find($countryNumber)
        ;

        if (null === $country) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An country with number "%s" does not exist!',
                $countryNumber
            ));
        }

        return $country;
    }
}