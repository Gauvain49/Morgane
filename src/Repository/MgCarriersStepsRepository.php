<?php

namespace App\Repository;

use App\Entity\MgCarriersSteps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCarriersSteps|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCarriersSteps|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCarriersSteps[]    findAll()
 * @method MgCarriersSteps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCarriersStepsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCarriersSteps::class);
    }

    // /**
    //  * @return MgCarriersSteps[] Returns an array of MgCarriersSteps objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MgCarriersSteps
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
