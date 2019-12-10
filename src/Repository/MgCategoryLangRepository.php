<?php

namespace App\Repository;

use App\Entity\MgCategoryLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCategoryLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCategoryLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCategoryLang[]    findAll()
 * @method MgCategoryLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCategoryLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCategoryLang::class);
    }

    // /**
    //  * @return MgCategoryLang[] Returns an array of MgCategoryLang objects
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
    public function findOneBySomeField($value): ?MgCategoryLang
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
