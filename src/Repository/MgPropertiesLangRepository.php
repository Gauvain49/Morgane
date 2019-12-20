<?php

namespace App\Repository;

use App\Entity\MgPropertiesLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgPropertiesLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgPropertiesLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgPropertiesLang[]    findAll()
 * @method MgPropertiesLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgPropertiesLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgPropertiesLang::class);
    }

    // /**
    //  * @return MgPropertiesLang[] Returns an array of MgPropertiesLang objects
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
    public function findOneBySomeField($value): ?MgPropertiesLang
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
