<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Filter;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function findByFilter(User $user, Filter $filter = null): Query
    {
        $where = null;
        $parameters = [];

        if ($filter) {
            if ($filter->getName()) {
                $where = 'c.name LIKE :name';
                $parameters = array_merge($parameters, ['name' => '%'.$filter->getName().'%']);
            }

            if ($filter->getSector()) {
                $where .= ($where) ? ' AND ' : '';
                $where .= 'c.sector = :sector';
                $parameters = array_merge($parameters, ['sector' => $filter->getSector()]);
            }

            return  $this->createQueryBuilder('c')
                ->where($where)
                ->setParameters($parameters)
                ->getQuery();
        }

        if (in_array(User::ROLE_CLIENT, $user->getRoles(), true)) {
            return $this->createQueryBuilder('c')
                ->innerJoin('c.sector', 'sector')
                ->innerJoin('sector.users', 'user')
                ->where('user.id = :user')
                ->setParameters(['user' => $user->getId()])
                ->getQuery();
        }

        return $this->createQueryBuilder('c')->getQuery();
    }
}
