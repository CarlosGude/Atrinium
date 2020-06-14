<?php

namespace App\Repository;

use App\Entity\BCEImport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BCEImport|null find($id, $lockMode = null, $lockVersion = null)
 * @method BCEImport|null findOneBy(array $criteria, array $orderBy = null)
 * @method BCEImport[]    findAll()
 * @method BCEImport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BCEImportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BCEImport::class);
    }

    // /**
    //  * @return BCEImport[] Returns an array of BCEImport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BCEImport
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
