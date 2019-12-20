<?php

namespace App\Repository;

use App\Entity\MgPropertiesContents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgPropertiesContents|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgPropertiesContents|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgPropertiesContents[]    findAll()
 * @method MgPropertiesContents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgPropertiesContentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgPropertiesContents::class);
    }

    // /**
    //  * @return MgPropertiesContents[] Returns an array of MgPropertiesContents objects
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
    public function findOneBySomeField($value): ?MgPropertiesContents
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
