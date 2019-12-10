<?php

namespace App\Repository;

use App\Entity\MgProductsLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgProductsLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgProductsLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgProductsLang[]    findAll()
 * @method MgProductsLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgProductsLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgProductsLang::class);
    }

    // /**
    //  * @return MgProductsLang[] Returns an array of MgProductsLang objects
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
    public function findOneBySomeField($value): ?MgProductsLang
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
