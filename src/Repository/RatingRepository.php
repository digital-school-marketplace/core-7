<?php

namespace App\Repository;

use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function findBySellerAndRater(User $seller, User $rater): object
    {
        return $this->findOneBy(['seller' => $seller, 'rater' => $rater]);
    }

    public function getAverageScoreForSeller(User $seller): ?float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.score)')
            ->where('r.seller = :seller')
            ->setParameter('seller', $seller)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? round((float) $result, 1) : null;
    }
}
