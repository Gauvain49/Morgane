<?php

namespace App\Repository;

use App\Entity\MgTaxes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgTaxes|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgTaxes|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgTaxes[]    findAll()
 * @method MgTaxes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgTaxesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgTaxes::class);
    }

    // /**
    //  * @return MgTaxes[] Returns an array of MgTaxes objects
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
    public function findOneBySomeField($value): ?MgTaxes
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
