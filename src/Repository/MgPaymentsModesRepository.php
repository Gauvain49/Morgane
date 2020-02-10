<?php

namespace App\Repository;

use App\Entity\MgPaymentsModes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgPaymentsModes|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgPaymentsModes|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgPaymentsModes[]    findAll()
 * @method MgPaymentsModes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgPaymentsModesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgPaymentsModes::class);
    }

    // /**
    //  * @return MgPaymentsModes[] Returns an array of MgPaymentsModes objects
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
    public function findOneBySomeField($value): ?MgPaymentsModes
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
