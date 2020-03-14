<?php
// src/Form/DataTransformer/IssueToNumberTransformer.php
namespace App\Form\DataTransformer;

use App\Entity\MgDepartmentsFrench;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DepartmentToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (department) to a string (number).
     *
     * @param  Issue|null $department
     * @return string
     */
    public function transform($department)
    {
        if (null === $department) {
            return '';
        }

        return $department->getId();
    }

    /**
     * Transforms a string (number) to an object (department).
     *
     * @param  string $departmentNumber
     * @return MgDepartmentsFrench|null
     * @throws TransformationFailedException if object (department) is not found.
     */
    public function reverseTransform($departmentNumber)
    {
        // no issue number? It's optional, so that's ok
        if (!$departmentNumber) {
            return;
        }

        $department = $this->entityManager
            ->getRepository(MgDepartmentsFrench::class)
            // query for the department with this id
            ->find($departmentNumber)
        ;

        if (null === $department) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An department with number "%s" does not exist!',
                $departmentNumber
            ));
        }

        return $department;
    }
}