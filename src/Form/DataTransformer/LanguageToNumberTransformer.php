<?php
// src/Form/DataTransformer/IssueToNumberTransformer.php
namespace App\Form\DataTransformer;

use App\Entity\MgLanguages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class LanguageToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (language) to a string (number).
     *
     * @param  Issue|null $language
     * @return string
     */
    public function transform($language)
    {
        if (null === $language) {
            return '';
        }

        return $language->getId();
    }

    /**
     * Transforms a string (number) to an object (language).
     *
     * @param  string $languageNumber
     * @return MgLanguages|null
     * @throws TransformationFailedException if object (language) is not found.
     */
    public function reverseTransform($languageNumber)
    {
        // no issue number? It's optional, so that's ok
        if (!$languageNumber) {
            return;
        }

        $language = $this->entityManager
            ->getRepository(MgLanguages::class)
            // query for the language with this id
            ->find($languageNumber)
        ;

        if (null === $language) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An language with number "%s" does not exist!',
                $languageNumber
            ));
        }

        return $language;
    }
}