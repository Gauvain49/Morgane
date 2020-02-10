<?php

namespace App\Repository;

use App\Entity\MgCountriesLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCountriesLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCountriesLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCountriesLang[]    findAll()
 * @method MgCountriesLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCountriesLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCountriesLang::class);
    }

    // /**
    //  * @return MgCountriesLang[] Returns an array of MgCountriesLang objects
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
    public function findOneBySomeField($value): ?MgCountriesLang
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
