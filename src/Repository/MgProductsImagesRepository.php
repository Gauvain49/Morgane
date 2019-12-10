<?php

namespace App\Repository;

use App\Entity\MgProductsImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgProductsImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgProductsImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgProductsImages[]    findAll()
 * @method MgProductsImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgProductsImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgProductsImages::class);
    }

    // /**
    //  * @return MgProductsImages[] Returns an array of MgProductsImages objects
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
    public function findOneBySomeField($value): ?MgProductsImages
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
