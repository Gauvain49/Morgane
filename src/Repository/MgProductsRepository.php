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
     * Affiche les produits du catalogue pour le front (par gamme)
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

    /**
     * Recupère un produit par son ID avec plus de contenu qu'in simple find()
     */
    public function getProduct($id, $lang)
    {
        $q = $this->createQueryBuilder('p')
            ->join('p.taxe', 't')
            ->addSelect('t')
            ->join('p.contents', 'l')
            ->addSelect('l')
            ->where('p.id = :id')
            ->andWhere('l.lang = :lang')
            ->setParameter('id', $id)
            ->setParameter('lang', $lang)
            ->getQuery()
            ->getOneOrNullResult();
        return $q;
    }

    /**
     * Affiche les produits du catalogue pour le front (par gamme)
     */
    public function getProductByIds($ids, $limit = null, $start = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.supplier', 's')
            ->addSelect('s')
            ->join('p.taxe', 't')
            ->addSelect('t');
        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('p.offline', 0),
            $qb->expr()->lte('p.date_publish', ':date'),
            $qb->expr()->in('p.id', $ids)
            )
        );
        if (!is_null($limit) && !is_null($start)) {
            $qb->setMaxResults($limit);
            $qb->setFirstResult($start);
        }
        $qb->orderBy('p.selling_price_all_taxes', 'ASC');
        //$qb->setParameter('type', 'master');
        $qb->setParameter('date', new \Datetime());
        $q = $qb->getQuery()->execute();
        return $q;
    }

    /**
     * Recherche de produit par nom grâce à un formulaire
     */
    public function searchProduct($search, $limit = null, $start = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.supplier', 's')
            ->addSelect('s')
            ->join('p.taxe', 't')
            ->addSelect('t')
            ->join('p.contents', 'l')
            ->addSelect('l');
        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('p.offline', 0),
            $qb->expr()->lte('p.date_publish', ':date'),
            $qb->expr()->like('l.name', ':search')
            )
        );
        if (!is_null($limit) && !is_null($start)) {
            $qb->setMaxResults($limit);
            $qb->setFirstResult($start);
        }
        $qb->orderBy('p.date_publish', 'DESC');
        //$qb->setParameter('type', 'master');
        $qb->setParameter('date', new \Datetime());
        $qb->setParameter('search', "%$search%");
        $q = $qb->getQuery()->execute();
        return $q;
    }

    /**
     * Retourne les prix d'un produit (HT, TTC) avec ses remises
     * @param int $id est l'id du produit
     * @param float $rateGroupCustomer est le taux de remise éventuel pour un client
     * @param string $namegroupCustomer est le nom du groupe auquel appartient le client
     */
    public function getPrice($product, $rateGroupCustomer = 0, $namegroupCustomer = false): array
    {
        $prices = [];
        //La nature du discount reprendre son montant en BDD, son type et son genre (genre = remise du produit, remise du groupe client, etc.)
        $detailDiscount = [];
        // On commence par calculé le prix avec les éventuelles remise du produit
        if ($product->getDiscount() == 0 || is_null($product->getDiscount())) {
                  $priceNet = $product->getSellingPrice();
                  $priceNetAllTaxes = $product->getSellingPriceAllTaxes();
        } else {//... s'il y en a une
            $calculPrice = $this->calculDiscountPriceAT($product->getDiscount(), $product->getDiscountType(), $product->getDiscountOnTaxe(), $product->getTaxe()->getTaxeRate(), $product->getSellingPrice(), $product->getSellingPriceAllTaxes());
            $priceNet = $calculPrice['priceNet'];
            $priceNetAllTaxes = $calculPrice['priceAllTaxes'];
            $detailDiscount = ['discount' => $product->getDiscount(), 'type' => $product->getDiscountType(), 'origin' => 'product'];
        }

        //Puis celui avec une éventuelle remise groupe client. Si cette dernière est plus élevée que la remise produit, elle l'écrase
        if ($rateGroupCustomer > 0) {
            $calculPriceGroup = $this->calculDiscountPriceAT($rateGroupCustomer, 'percent', 1, $product->getTaxe()->getTaxeRate(), $product->getSellingPrice(), $product->getSellingPriceAllTaxes());
            // Si le prix net (qui prend déjà en compte une eventuelle remise produit) est supérieur
            // au prix calculé avec la remise groupe client, c'est cette dernière qui est prise en compte
            if ($calculPriceGroup['priceAllTaxes'] < $priceNetAllTaxes) {
                $priceNet = $calculPriceGroup['priceNet'];
                $priceNetAllTaxes = $calculPriceGroup['priceAllTaxes'];
                $detailDiscount = ['discount' => $rateGroupCustomer, 'type' => 'percent', 'origin' => 'group' . $namegroupCustomer];
            }
        }

        $prices = [
            'grossPrice' => $product->getSellingPrice(),
            'grossPriceTax' => $product->getSellingPriceAllTaxes() - $product->getSellingPrice(),
            'grossPriceAllTaxes' => $product->getSellingPriceAllTaxes(),
            'priceNet' => $priceNet,
            'netPriceTax' => $priceNetAllTaxes - $priceNet,
            'priceNetAllTaxes' => $priceNetAllTaxes,
            'detailDiscount' => $detailDiscount
        ];

        return $prices;
    }

    /**
     * Calcul d'un prix TTC remisé
     * @param $type est le type de remise
     * @param $onTaxe définie si la remise se calcul sur le HT ou TTC
     * @param $discount est le montant ou taux de remise
     * @param $taxe est la taxe du produit
     * @param $net est le prix HT du produit
     * @param $allTaxes est le prix TTC du produit
     */
    public function calculDiscountPriceAT($discount, $type, $onTaxe, $taxe, $net, $allTaxes): array
    {
        $priceNet = 0;
        $priceTaxe = 0;
        $priceAllTaxes = 0;
        //Si la réduction est un montant
        if ($type == 'amount') {
            //Si c'est sur le HT
            if ($onTaxe == 0) {
                //On calcul d'abord le net
                //Puis on calcul le ttc
                $priceNet = round($net - $discount, 2);
                $priceTaxe = round($priceNet * $taxe / 100, 2);
                $priceAllTaxes = $priceNet + $priceTaxe;
            //Si c'est sur le ttc
            } else {
                $priceAllTaxes = round($allTaxes - $discount, 2);
                $priceTaxe = round($priceAllTaxes / (100 + $taxe) * $taxe, 2);
                $priceNet = $priceAllTaxes - $priceTaxe;
            }
        //Si la réduction est un pourcentage
        } else {
            if ($onTaxe == 0) {
                $priceNet = round($net - ($net * $discount / 100), 2);
                $priceTaxe = round($priceNet * $taxe / 100, 2);
                $priceAllTaxes = $priceNet + $priceTaxe;
            } else {
                $priceAllTaxes = round($allTaxes - ($allTaxes * $discount / 100), 2);
                $priceTaxe = round($priceAllTaxes / (100 + $taxe) * $taxe, 2);
                $priceNet = $priceAllTaxes - $priceTaxe;
            }
        }

        return ['priceNet' => $priceNet, 'priceTaxe' => $priceTaxe, 'priceAllTaxes' => $priceAllTaxes];
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
