<?php

namespace App\Repository;

use App\Entity\Sector;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sector|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sector|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sector[]    findAll()
 * @method Sector[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sector::class);
    }

    public function findByUserAuthorized(User $user)
    {
        if(in_array(User::ROLE_ADMIN,$user->getRoles(),true)){
            return $this->createQueryBuilder('s');
        }

        return $this->createQueryBuilder('s')
            ->innerJoin('s.users','u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user->getId())
        ;
    }

}
