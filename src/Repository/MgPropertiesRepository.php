<?php

namespace App\Repository;

use App\Entity\MgProperties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgProperties|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgProperties|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgProperties[]    findAll()
 * @method MgProperties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgPropertiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgProperties::class);
    }

    // /**
    //  * @return MgProperties[] Returns an array of MgProperties objects
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
    public function findOneBySomeField($value): ?MgProperties
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
