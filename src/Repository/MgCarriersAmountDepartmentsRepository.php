<?php

namespace App\Repository;

use App\Entity\MgCarriersAmountDepartments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCarriersAmountDepartments|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCarriersAmountDepartments|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCarriersAmountDepartments[]    findAll()
 * @method MgCarriersAmountDepartments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCarriersAmountDepartmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCarriersAmountDepartments::class);
    }

    // /**
    //  * @return MgCarriersAmountDepartments[] Returns an array of MgCarriersAmountDepartments objects
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
    public function findOneBySomeField($value): ?MgCarriersAmountDepartments
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
