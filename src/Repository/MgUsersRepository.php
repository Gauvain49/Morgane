<?php

namespace App\Repository;

use App\Entity\MgUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgUsers[]    findAll()
 * @method MgUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgUsers::class);
    }

    /**
     * Retourne les utilisateurs ayant accès au backoffice
     */
   /* public function getUsersAdmin()
    {
        return $this->createQueryBuilder('u')
            ->where('role = $$')
            ->getQuery()
            ->getResult()
        ;
    }*/

    // /**
    //  * @return MgUsers[] Returns an array of MgUsers objects
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
    public function findOneBySomeField($value): ?MgUsers
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
