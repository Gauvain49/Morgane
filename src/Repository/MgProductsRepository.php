<?php

namespace App\Repository;

use App\Entity\MgProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgProducts[]    findAll()
 * @method MgProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgProducts::class);
    }

    // /**
    //  * @return MgProducts[] Returns an array of MgProducts objects
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
    public function findOneBySomeField($value): ?MgProducts
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
