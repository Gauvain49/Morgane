<?php

namespace App\Repository;

use App\Entity\MgMovementsStocks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgMovementsStocks|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgMovementsStocks|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgMovementsStocks[]    findAll()
 * @method MgMovementsStocks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgMovementsStocksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgMovementsStocks::class);
    }

    // /**
    //  * @return MgMovementsStocks[] Returns an array of MgMovementsStocks objects
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
    public function findOneBySomeField($value): ?MgMovementsStocks
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
