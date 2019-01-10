<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\AbstractQuery;

class AccountRepository extends ServiceEntityRepository
{
    /**
     * AccountRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param Country $country
     *
     * @return AbstractQuery
     */
    public function getPaginatedAccountsByCountryQuery(Country $country = null) : AbstractQuery
    {
        if ($country) {
            return $this->createQueryBuilder('a')
                ->where('a.country = :country')
                ->setParameter('country', $country)
                ->getQuery();
        } else {
            return $this->createQueryBuilder('a')
                ->orderBy('a.country')
                ->getQuery();
        }
    }

    /**
     * Return the nb of accounts by countries
     *
     * @return mixed
     */
    public function countByCountry()
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.country', 'c')
            ->select('count(a) as nb')
            ->addSelect('c.code, c.name')
            ->groupBy('a.country')
            ->orderBy('c.name');

        return $qb->getQuery()->getResult();
    }
}
