<?php

namespace App\Repository;

use App\Entity\MgCarriersLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCarriersLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCarriersLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCarriersLang[]    findAll()
 * @method MgCarriersLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCarriersLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCarriersLang::class);
    }

    // /**
    //  * @return MgCarriersLang[] Returns an array of MgCarriersLang objects
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
    public function findOneBySomeField($value): ?MgCarriersLang
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
