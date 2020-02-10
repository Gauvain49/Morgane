<?php

namespace App\Repository;

use App\Entity\MgOrdersPayments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgOrdersPayments|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgOrdersPayments|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgOrdersPayments[]    findAll()
 * @method MgOrdersPayments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgOrdersPaymentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgOrdersPayments::class);
    }

    // /**
    //  * @return MgOrdersPayments[] Returns an array of MgOrdersPayments objects
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
    public function findOneBySomeField($value): ?MgOrdersPayments
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
