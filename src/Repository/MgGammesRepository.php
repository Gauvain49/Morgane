<?php

namespace App\Repository;

use App\Entity\MgGammes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgGammes|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgGammes|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgGammes[]    findAll()
 * @method MgGammes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgGammesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgGammes::class);
    }

    // /**
    //  * @return MgGammes[] Returns an array of MgGammes objects
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
    public function findOneBySomeField($value): ?MgGammes
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
