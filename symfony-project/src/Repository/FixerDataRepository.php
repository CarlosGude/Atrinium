<?php

namespace App\Repository;

use App\Entity\FixerData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FixerData|null find($id, $lockMode = null, $lockVersion = null)
 * @method FixerData|null findOneBy(array $criteria, array $orderBy = null)
 * @method FixerData[]    findAll()
 * @method FixerData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FixerDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FixerData::class);
    }

    // /**
    //  * @return FixerData[] Returns an array of FixerData objects
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
    public function findOneBySomeField($value): ?FixerData
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
