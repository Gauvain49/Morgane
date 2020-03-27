<?php

namespace App\Repository;

use App\Entity\MgOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgOrders|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgOrders|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgOrders[]    findAll()
 * @method MgOrders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgOrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgOrders::class);
    }

    /**
     * Calcul le numéro de la commande => AAAAMMNNNNN
     * Ce numéro se décompose de l'année en cours (AAAA), mois en cours (MM), et numéro de la dernière commande + 1 sur 5 chiffres(NNNNN)
     */
    public function getNumOrder()
    {
        $lastOrder = $this->findOneBy([], ['id' => 'DESC']);
        if (!empty($lastOrder)) {
            $lastNum = substr($lastOrder->getNum(), 6);
            $newNum = intval($lastNum) + 1;
        } else {
            $newNum = 1;
        }
        //$numOrder = date("Y") . date("m") . sprintf("%'.04d\n", $newNum);
        return sprintf("%'.06d", $newNum);
        //return $newNum;
    }

    public function salesOfMounth($month)
    {
        return $this->createQueryBuilder('o')
            ->select('o.total_price_all_taxes')
            //->expr()->sum('o.total_price_all_taxes')
            ->where('MONTH(o.date_add) = :month')
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult()
        ;
    }

    public function salesOfYear($year)
    {
        return $this->createQueryBuilder('o')
            ->select('o.date_add, o.total_price_all_taxes')
            //->expr()->sum('o.total_price_all_taxes')
            ->where('YEAR(o.date_add) = :year')
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return MgOrders[] Returns an array of MgOrders objects
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
    public function findOneBySomeField($value): ?MgOrders
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
