<?php

namespace App\Repository;

use App\Entity\Account;
//use App\Entity\Country;
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
     * @param string $country
     *
     * @return AbstractQuery
     */
    public function getPaginatedAccountsByCountryQuery(string $country = null) : AbstractQuery
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
            ->select('count(a) as nb')
            ->addSelect('a.country as code')
            ->groupBy('a.country');

        return $qb->getQuery()->getResult();
    }
}
