<?php

namespace App\Repository;

use App\Entity\MgPaypal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgPaypal|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgPaypal|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgPaypal[]    findAll()
 * @method MgPaypal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgPaypalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgPaypal::class);
    }

    // /**
    //  * @return MgPaypal[] Returns an array of MgPaypal objects
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
    public function findOneBySomeField($value): ?MgPaypal
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
