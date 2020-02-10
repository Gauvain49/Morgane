<?php

namespace App\Repository;

use App\Entity\MgOrdersContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgOrdersContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgOrdersContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgOrdersContent[]    findAll()
 * @method MgOrdersContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgOrdersContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgOrdersContent::class);
    }

    // /**
    //  * @return MgOrdersContent[] Returns an array of MgOrdersContent objects
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
    public function findOneBySomeField($value): ?MgOrdersContent
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
