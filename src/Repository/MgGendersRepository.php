<?php

namespace App\Repository;

use App\Entity\MgGenders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgGenders|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgGenders|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgGenders[]    findAll()
 * @method MgGenders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgGendersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgGenders::class);
    }

    // /**
    //  * @return MgGenders[] Returns an array of MgGenders objects
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
    public function findOneBySomeField($value): ?MgGenders
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
