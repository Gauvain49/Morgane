<?php

namespace App\Repository;

use App\Entity\MgParametersAddresses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgParametersAddresses|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgParametersAddresses|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgParametersAddresses[]    findAll()
 * @method MgParametersAddresses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgParametersAddressesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgParametersAddresses::class);
    }

    // /**
    //  * @return MgParametersAddresses[] Returns an array of MgParametersAddresses objects
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
    public function findOneBySomeField($value): ?MgParametersAddresses
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
