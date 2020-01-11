<?php

namespace App\Repository;

use App\Entity\MgPosts;
use App\Services\TreeStructure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgPosts|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgPosts|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgPosts[]    findAll()
 * @method MgPosts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgPostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, TreeStructure $tree)
    {
        parent::__construct($registry, MgPosts::class);
        $this->tree = $tree;
    }

    /**
     * Récupération des pages par arborescence
     */
    public function findAllByArborescence()
    {
        $array = [];
        $pages = $this->findBy(['type' => 'page'], ['position' => 'ASC']);
        foreach ($pages as $page) {
            $chose = !is_null($page->getParent()) ? $this->find($page->getParent())->getId() : 0;
            $truc = $this->find($page->getId());
            foreach ($page->getContents() as $v) {
                if ($v->getLang()->getId() == 1) {
                    $name = $v->getTitle();
                }
            }
            $array[] = [
                'parent' => $chose,
                'id' => $page->getId(),
                'nom' => $name
            ];
        }
        $checkbox2 = $this->tree->treeStructure(0, 0, $array, '—');
        $checkbox3 = [];
        foreach ($checkbox2 as $key => $value) {
            $checkbox3[$key] = $this->find($value);
        }
        return $checkbox3;
    }

    /**
     * Attribution automatique d'une position
     */
    public function setPosition($parent)
    {
        $position = 1;
        //On récupère la dernière position en appelant les pages
        //du même parent
        $lastPosition = $this->findOneBy(['type' => 'page', 'parent' => $parent], ['position' => 'DESC']);
        if (!empty($lastPosition)) {
            $position = $lastPosition->getPosition()+1;
        }
        return $position;
    }

    // /**
    //  * @return MgPosts[] Returns an array of MgPosts objects
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
    public function findOneBySomeField($value): ?MgPosts
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
