<?php
// src/Form/DataTransformer/IssueToNumberTransformer.php
namespace App\Form\DataTransformer;

use App\Entity\MgCategories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CategorieToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (category) to a string (number).
     *
     * @param  Issue|null $category
     * @return string
     */
    public function transform($category)
    {
        dump($category);
        if (null === $category) {
            return '';
        }

        return $category->getId();
    }

    /**
     * Transforms a string (number) to an object (category).
     *
     * @param  string $categoryNumber
     * @return MgCategories|null
     * @throws TransformationFailedException if object (category) is not found.
     */
    public function reverseTransform($categoryNumber)
    {
        // no issue number? It's optional, so that's ok
        if (!$categoryNumber) {
            return;
        }

        $category = $this->entityManager
            ->getRepository(MgCategories::class)
            // query for the category with this id
            ->find($categoryNumber)
        ;

        if (null === $category) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An category with number "%s" does not exist!',
                $categoryNumber
            ));
        }

        return $category;
    }
}