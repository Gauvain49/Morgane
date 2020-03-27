<?php
namespace App\Form\DataTransformer;

use App\Entity\MgRegionsFrench;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RegionToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (region) to a string (number).
     *
     * @param  Issue|null $region
     * @return string
     */
    public function transform($region)
    {
        if (null === $region) {
            return '';
        }

        return $region->getId();
    }

    /**
     * Transforms a string (number) to an object (region).
     *
     * @param  string $regionNumber
     * @return MgRegionsFrench|null
     * @throws TransformationFailedException if object (region) is not found.
     */
    public function reverseTransform($regionNumber)
    {
        // no issue number? It's optional, so that's ok
        if (!$regionNumber) {
            return;
        }

        $region = $this->entityManager
            ->getRepository(MgRegionsFrench::class)
            // query for the region with this id
            ->find($regionNumber)
        ;

        if (null === $region) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An region with number "%s" does not exist!',
                $regionNumber
            ));
        }

        return $region;
    }
}