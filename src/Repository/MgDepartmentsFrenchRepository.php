<?php

namespace App\Repository;

use App\Entity\MgDepartmentsFrench;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgDepartmentsFrench|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgDepartmentsFrench|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgDepartmentsFrench[]    findAll()
 * @method MgDepartmentsFrench[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgDepartmentsFrenchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgDepartmentsFrench::class);
    }

    // /**
    //  * @return MgDepartmentsFrench[] Returns an array of MgDepartmentsFrench objects
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
    public function findOneBySomeField($value): ?MgDepartmentsFrench
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
