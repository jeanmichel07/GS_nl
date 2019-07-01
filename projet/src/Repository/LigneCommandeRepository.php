<?php

namespace App\Repository;

use App\Entity\LigneCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LigneCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneCommande[]    findAll()
 * @method LigneCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneCommandeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LigneCommande::class);
    }

    public function findLine($idcommande):array
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT l FROM App\Entity\LigneCommande l where l.commande= :id')
            ->setParameter('id',$idcommande);
        return $query->execute();
    }

    public function update($idProduit)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        return $queryBuilder->update('App\Entity\Produit', 'p')->set('p.emplacement', 'vendu')
            ->where($queryBuilder->expr()->eq('p.id', ':produitId'))
            ->setParameter('produitId',$idProduit);
    }
   public function findLigneCommande()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function sumcomm($idcommande):array
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT SUM(p.prix) as t FROM App\Entity\produit p INNER JOIN App\Entity\LigneCommande l where p.id=l.produit and l.commande= :id')
            ->setParameter('id',$idcommande);
        return $query->execute();
    }

    // /**
    //  * @return LigneCommande[] Returns an array of LigneCommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LigneCommande
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
