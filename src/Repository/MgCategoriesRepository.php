<?php

namespace App\Repository;

use App\Entity\MgCategories;
use App\Services\TreeStructure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCategories[]    findAll()
 * @method MgCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCategoriesRepository extends ServiceEntityRepository
{
    private $tree;

    public function __construct(ManagerRegistry $registry, TreeStructure $tree)
    {
        parent::__construct($registry, MgCategories::class);
        $this->tree = $tree;
    }

    /**
     * Attribution du niveau d'arborescence d'une catégorie
     */
    public function setLevel($parent)
    {
        $level = 1;
        //On récupère le parent
        $catParent = $this->find($parent);
        //Si le parent n'est pas la racine (id = 1), on incrémente le niveau
        if ($catParent->getId() != 1) {
            $level = $catParent->getLevel()+1;
        }
        return $level;
    }

    /**
     * Attribution du niveau d'arborescence d'une catégorie
     */
    public function setPosition($parent)
    {
        $position = 10;
        //On récupère la dernière position en appelant les catégories
        //du même parent
        $cat = $this->findOneBy(['parent' => $parent], ['position' => 'DESC']);
        //Si la catégorie récupérée  n'est pas la racine (id = 1), on incrémente la position de 10
        if (!empty($cat)) {
            if ($cat->getId() != 1) {
                $position = $cat->getPosition()+10;
            }
        }
        return $position;
    }

    /**
     * Récupération des Catégories par arborescences
     */
    public function findAllByArborescence($type)
    {
        $array = [];
        $categories = $this->findBy(['type' => $type], ['position' => 'ASC']);
        foreach ($categories as $cat) {
            if ($cat->getId() != 1) {
                $chose = $this->findOneById($cat->getParent());
                $truc = $this->findOneById($cat->getId());
                foreach ($cat->getContents() as $key => $value) {
                    if ($value->getLang()->getId() == 1) {
                        $name = $value->getName();
                    }
                }
                $array[] = [
                    'parent' => $chose->getId(),
                    'id' => $cat->getId(),
                    'nom' => $name
                ];
            }
        }
        $checkbox2 = $this->tree->treeStructure(1, 0, $array, ' ');
        $checkbox3 = [];
        foreach ($checkbox2 as $key => $value) {
            $checkbox3[$key] = $this->find($value);
        }
        //dump($checkbox3);
        return $checkbox3;
        /*$in = implode(', ', $checkbox2);
        $q = $this->createQueryBuilder('c');
        return    $q->join('c.contents', 'l')
            ->addSelect('l')
            ->where($q->expr()->in('c.id', $in))
            ->andWhere('l.lang = 1')
            ->orderBy('c.parent', 'ASC')
            ->getQuery()
            ->getResult()
        ;*/

        //return $q;
    }

    // /**
    //  * @return MgCategories[] Returns an array of MgCategories objects
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
    public function findOneBySomeField($value): ?MgCategories
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
