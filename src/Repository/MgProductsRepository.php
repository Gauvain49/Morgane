<?php

namespace App\Repository;

use App\Entity\MgProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgProducts[]    findAll()
 * @method MgProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgProducts::class);
    }

    /**
     * Affiche les produits du catalogue pour le front
     */
    public function catalogFront($limit, $start, $gamme = false)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.supplier', 's')
            ->addSelect('s')
            ->join('p.taxe', 't')
            ->addSelect('t');
        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('p.offline', 0),
            $qb->expr()->lte('p.date_publish', ':date')
            )
        );
        if ($gamme) {
            $qb->andWhere('p.gamme IS NOT NULL');
        }
        if (!is_null($limit) && !is_null($start)) {
            $qb->setMaxResults($limit);
            $qb->setFirstResult($start);
        }
        $qb->orderBy('p.date_publish', 'DESC');
        //$qb->setParameter('type', 'master');
        $qb->setParameter('date', new \Datetime());
        $q = $qb->getQuery()->execute();
        return $q;
    }

    // /**
    //  * @return MgProducts[] Returns an array of MgProducts objects
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
    public function findOneBySomeField($value): ?MgProducts
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
