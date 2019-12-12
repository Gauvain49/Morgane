<?php

namespace App\Repository;

use App\Entity\MgProductsImages;
use App\Services\MimeType;
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
    const SMALL = '_small';
    const MEDIUM = '_medium';
    const LARGE = '_large';
    const ORIGINE = '';
    const SIZES = [self::SMALL, self::MEDIUM, self::LARGE, self::ORIGINE];
    protected $mimeType;

    public function __construct(ManagerRegistry $registry, MimeType $mimeType)
    {
        parent::__construct($registry, MgProductsImages::class);
        $this->mimeType = $mimeType;
    }

    public function getImgBySize($path, $id, $size, $square = '')
    {
        $img = $this->find($id);
        if (strlen($img->getId()) > 1) {
            $field = '';
            $field_vignette = str_split($img->getId());
            foreach ($field_vignette as $dossier) {
                $field .= $dossier . '/';
            }
            $imgPath = $this->checkImgSize($path, $field . $img->getId(), $size, $square, $this->mimeType->getExtension($img->getMimeType()));
        } else {
            $imgPath = $this->checkImgSize($path, $img->getId() . '/' . $img->getId(), $size, $square, $this->mimeType->getExtension($img->getMimeType()));
        }
        return $imgPath;
    }

    public function checkImgSize($path, $image, $size, $square, $ext)
    {
        $key = array_search($size, self::SIZES);
        $img = $image . $size . $square . $ext;
        if (!is_file($path . $img)) {
            while (!is_file($path . $img)) {
                $key++;
                if (self::SIZES[$key] == '') {
                    $square = '';
                }
                $img = $image . self::SIZES[$key] . $square . $ext;
                if($key > count(self::SIZES)-1) {
                    break;
                }
            }
        }
        return $img;
    }

    /**
     * Permet de récupérer une image par défaut afin de lui attribuer le statut de cover
     * @param id est l'image a extraire de la recherche (elle a déjà le statut de cover)
     * @param $product est le produit associé à l'image
     */
    public function findOneByNotId($id, $product)
    {
        return $this->createQueryBuilder('i')
            ->where('i.id <> :val')
            ->andWhere('i.product = :product')
            ->setParameter('val', $id)
            ->setParameter('product', $product)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
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
