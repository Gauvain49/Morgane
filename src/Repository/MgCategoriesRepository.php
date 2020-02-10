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
    private $children = [];

    public function __construct(ManagerRegistry $registry, TreeStructure $tree)
    {
        parent::__construct($registry, MgCategories::class);
        $this->tree = $tree;
    }

    public function buildMenuCat($lang)
    {
        return $this->createQueryBuilder('c')
            ->join('c.contents', 'l')
            ->addSelect('l')
            ->andWhere('c.active = 1')
            ->andWhere('l.lang = :lang')
            ->andWhere('c.id != 1')
            ->setParameter('lang', $lang)
            ->orderBy('c.position', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
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
     * Attribution automatique d'une position pour une catégorie racine
     */
    public function setPosition($parent, $type)
    {
        //Initialisation de la variable (utile pour la première création d'une catégorie)
        $position = 1;
        //On vérifie s'il existe déjà des catégories
        $checkCategories = $this->findBy(['type' => $type], ['position' => 'DESC']);
        if (count($checkCategories) > 0) {
            //Si la parent est la racine, on écrase la précédente position pour la mettre en dernier
            $checkCategories = current($checkCategories);
            $position = $checkCategories->getPosition() + 1;
            if ($parent->getId() != 1) {
                //Sinon, on vérifie si la catégorie parent possède déjà des enfants
                $catChildren = $this->findBy(['parent' => $parent, 'type' => $type], ['position' => 'DESC']);
                //S'il y en a, on écrase à nouveau la position pour la mettre à la fin de la catégorie parent
                if (count($catChildren) > 0) {
                    $catChildren = current($catChildren);
                    $position = $catChildren->getPosition() + 1;
                } else {
                    //Sinon, on met la positon juste après la parent
                    $catParent = $this->findOneBy(['id' => $parent, 'type' => $type], ['position' => 'DESC']);
                    $position = $catParent->getPosition() + 1;
                }
            }
        }
        return $position;
    }

    /**
     * Permuter des positions entre catégories
     */
   public function switchPosition($category, $update)
    {
        $updateCategories = [];
        //On récupère la catégorie voisine selon le niveau
        $categoryNeighbor = $this->categoryNeighbor($category->getLevel(), $category->getType(), $category->getPosition(), $update);
        $categoryNeighbor = current($categoryNeighbor);
        //Si le changement de position est 'down', on interverti les variables de catégorie voisine et courante
        if ($update == 'down') {
            $category = $this->find($categoryNeighbor);
            $categoryNeighbor = $this->categoryNeighbor($category->getLevel(), $category->getType(), $category->getPosition(), 'up');
            $categoryNeighbor = current($categoryNeighbor);
        }
        //... dont la position deviendra la nouvelle position de la cat courrante
        $newPositionCatCurrent = $categoryNeighbor->getPosition();
        /**
         * Catégorie(s) qui remonte(nt)
         */
        //On met à jour la position de la catégorie courante
        $updateCategories[$category->getId()] = $newPositionCatCurrent;
        //On récupère les enfants de la catégorie courante
        $fetchChildrenCurrent = $this->fetchChildren($category, $category->getType());
        $childrenCurrent = $this->getChildren();
        $newPositionChildCurrent = ++$newPositionCatCurrent;
        if (count($childrenCurrent) > 0) {
            foreach ($childrenCurrent as $childCurrent) {
                $updateCategories[$childCurrent->getId()] = $newPositionChildCurrent;
                ++$newPositionChildCurrent;
            }
        }
        //On vide la variable recueillant les enfants
        $this->emptyChildren();
        /**
         * Catégorie(s) qui redescende(nt)
         */
        //on reprend $categoryNeighbor pour lui attribuer la dernière position calculée par $newPositionChildrenCurrent
        $updateCategories[$categoryNeighbor->getId()] = $newPositionChildCurrent;
        //et on incrémente ses enfants par raport à cette nouvelle position
        $fetchChildren = $this->fetchChildren($categoryNeighbor, $category->getType());
        $children = $this->getChildren();
        if (count($children) > 0) {
            $newPositionChild = ++$newPositionChildCurrent;
            foreach ($children as $child) {
                $updateCategories[$child->getId()] = $newPositionChild;
                ++$newPositionChild;
            }                    
        }
        //On vide ensuite la variable recueillant les enfants
        $this->emptyChildren();
        return $updateCategories;
    }

    /**
     * Récupération des catégories dont il faut mettre à jour la position, en cascade
     */
    public function setPositionCascad($position, $id, $type)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.type = :type');
        if (!is_null($id)) {
            $qb->andWhere('c.id <> :id')
            ->setParameter('id', $id)
            ;
        }
        $qb->andWhere('c.position >= :position')
            ->setParameter('type', $type)
            ->setParameter('position', $position)
            ->orderBy('c.position', 'ASC')
        ;
        $q = $qb->getQuery()->execute();
        return $q;
    }

    /**
     * Récupération des enfants d'une catégorie
     */
    public function fetchChildren($id, $type) {
        $children = $this->findBy(['parent' => $id, 'type' => $type], ['position' => 'ASC']);
        //return count($children);
        if (!empty($children)) {
            foreach ($children as $child) {
                $this->children[] = $child;
                $this->fetchChildren($child->getId(), $child->getType());
            }
        }
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function emptyChildren(): self
    {
        $this->children = [];

        return $this;
    }

    /**
     * Récupération des catégories dont la position est supérieur à celle sélectionnée
     */
    public function getPositionUpper($position, $type)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.position > :position')
            ->andWhere('c.type = :type')
            ->setParameter('position', $position)
            ->setParameter('type', $type)
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupération de la catégorie voisine de même niveau
     */
    public function categoryNeighbor($level, $type, $position, $update)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.level = :level')
            ->andWhere('c.type = :type');
        if ($update == 'up') {
            $qb->andWhere('c.position < :position')
            ->orderBy('c.position', 'DESC');
        } elseif ($update == 'down') {
            $qb->andWhere('c.position > :position')
            ->orderBy('c.position', 'ASC');
        }
        $qb->setParameter('level', $level)
            ->setParameter('type', $type)
            ->setParameter('position', $position)
            ->setMaxResults(1);

        $q = $qb->getQuery()->getResult();
        return $q;
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
        return $checkbox3;

    }

    public function fetchByTreeStructure()
    {
        $q = $this->createQueryBuilder('c');
        return $q->join('c.contents', 'l')
            ->addSelect('l')
            //->where($q->expr()->in('c.id', $in))
            ->where($q->expr()->eq('c.type', ':type'))
            ->setParameter('type', 'product')
            ->andWhere('l.lang = 1')
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère une categorie par son slug
     **/
    public function getOneBySlug($slug, $lang)
    {
        return $this->createQueryBuilder('c')
            ->join('c.contents', 'l')
            ->addSelect('l')
            ->where('l.slug = :slug')
            ->andWhere('l.lang = :lang')
            ->setParameter('slug', $slug)
            ->setParameter('lang', $lang)
            ->getQuery()
            ->getOneOrNullResult()
            ;
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
