<?php

namespace App\Repository;

use App\Entity\MgOrdersCarriers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgOrdersCarriers|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgOrdersCarriers|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgOrdersCarriers[]    findAll()
 * @method MgOrdersCarriers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgOrdersCarriersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgOrdersCarriers::class);
    }

    // /**
    //  * @return MgOrdersCarriers[] Returns an array of MgOrdersCarriers objects
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
    public function findOneBySomeField($value): ?MgOrdersCarriers
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
