<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findStock($idCategorie)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery("SELECT COUNT(p) as s FROM App\Entity\Produit p WHERE p.emplacement='stock' and p.categorie=:id")
        ->setParameter('id',$idCategorie);
        return $query->execute();
    }
    public function findStockdetaille($idCategorie)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery("SELECT p FROM App\Entity\Produit p WHERE p.emplacement='stock' and p.categorie=:id")
            ->setParameter('id',$idCategorie);
        return $query->execute();
    }
    public function findProduit()
    {
        return $this->createQueryBuilder('p')
            ->groupBy('p.categorie')
            ->orderBy('p.categorie', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
