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
     * Récupère les pages pour le menu principale
     *
     */
    public function findPostsWithDateManage($type, $status)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('p.type', ':val'),
            $qb->expr()->lte('p.date_publish', ':date'),
            $qb->expr()->eq('p.status', ':status'),
            //$qb->expr()->neq('p.reserved', ':cgv'),
            $qb->expr()->orX(
                $qb->expr()->gt('p.date_expire', ':date'),
                $qb->expr()->isNull('p.date_expire')
                )
            ),
            $qb->expr()->orX(
                $qb->expr()->neq('p.reserved', ':cgv'),
                $qb->expr()->isNull('p.reserved')
                )
            )
        ;
        //$qb->andWhere('p.reserved = :cgv');
        $qb->orderBy('p.position', 'ASC');
        $qb->setParameter('status', 'publish');
        $qb->setParameter('val', $type);
        $qb->setParameter('date', new \Datetime());
        $qb->setParameter('cgv', 'CGV');
        $q = $qb->getQuery()->execute();
        return $q;
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

    /**
     * Récupère un post par son slug
     * @param $type est le type du post
     * @param $lang est la langue des contenus
     * @param $slug est le slug
     */
    public function findOnePostBySlug($type, $lang, $slug)
    {
        return $this->createQueryBuilder('p')
            ->join('p.contents', 'l')
            ->addSelect('l')
            ->where('p.type = :type')
            ->andWhere('l.lang = :lang')
            ->andWhere('l.slug = :slug')
            ->setParameter('type', $type)
            ->setParameter('lang', $lang)
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
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
