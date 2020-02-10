<?php

namespace App\Repository;

use App\Entity\MgCustomersAddresses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCustomersAddresses|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCustomersAddresses|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCustomersAddresses[]    findAll()
 * @method MgCustomersAddresses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCustomersAddressesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCustomersAddresses::class);
    }

    // /**
    //  * @return MgCustomersAddresses[] Returns an array of MgCustomersAddresses objects
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
    public function findOneBySomeField($value): ?MgCustomersAddresses
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
