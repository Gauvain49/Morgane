<?php

namespace App\Repository;

use App\Entity\MgParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgParameters|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgParameters|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgParameters[]    findAll()
 * @method MgParameters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgParametersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgParameters::class);
    }

    // /**
    //  * @return MgParameters[] Returns an array of MgParameters objects
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
    public function findOneBySomeField($value): ?MgParameters
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
