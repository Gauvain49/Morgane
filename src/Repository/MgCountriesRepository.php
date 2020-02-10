<?php

namespace App\Repository;

use App\Entity\MgCountries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCountries|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCountries|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCountries[]    findAll()
 * @method MgCountries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCountriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCountries::class);
    }

    // /**
    //  * @return MgCountries[] Returns an array of MgCountries objects
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
    public function findOneBySomeField($value): ?MgCountries
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
