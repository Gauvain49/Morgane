<?php

namespace App\Repository;

use App\Entity\MgPostsLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgPostsLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgPostsLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgPostsLang[]    findAll()
 * @method MgPostsLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgPostsLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgPostsLang::class);
    }

    // /**
    //  * @return MgPostsLang[] Returns an array of MgPostsLang objects
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
    public function findOneBySomeField($value): ?MgPostsLang
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
