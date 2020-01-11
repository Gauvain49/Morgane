<?php

namespace App\Repository;

use App\Entity\MgParametersAddressesTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgParametersAddressesTitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgParametersAddressesTitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgParametersAddressesTitle[]    findAll()
 * @method MgParametersAddressesTitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgParametersAddressesTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgParametersAddressesTitle::class);
    }

    // /**
    //  * @return MgParametersAddressesTitle[] Returns an array of MgParametersAddressesTitle objects
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
    public function findOneBySomeField($value): ?MgParametersAddressesTitle
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
