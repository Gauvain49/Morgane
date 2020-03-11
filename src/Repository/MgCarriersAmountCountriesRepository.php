<?php

namespace App\Repository;

use App\Entity\MgCarriersAmountCountries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCarriersAmountContries|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCarriersAmountContries|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCarriersAmountContries[]    findAll()
 * @method MgCarriersAmountContries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCarriersAmountCountriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCarriersAmountCountries::class);
    }

    // /**
    //  * @return MgCarriersAmountContries[] Returns an array of MgCarriersAmountContries objects
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
    public function findOneBySomeField($value): ?MgCarriersAmountContries
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
