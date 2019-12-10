<?php

namespace App\Repository;

use App\Entity\MgGammesLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgGammesLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgGammesLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgGammesLang[]    findAll()
 * @method MgGammesLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgGammesLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgGammesLang::class);
    }

    // /**
    //  * @return MgGammesLang[] Returns an array of MgGammesLang objects
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
    public function findOneBySomeField($value): ?MgGammesLang
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
