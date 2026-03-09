<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find users sorted by email
     */
    public function findAllSortedByEmail(string $order = 'ASC'): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.email', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find users with pagination
     */
    public function findPaginated(int $page = 1, int $limit = 10): array
    {
        return $this->createQueryBuilder('u')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find user with their listings (optimized)
     */
    public function findUserWithListings(int $userId): ?User
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.listings', 'l')
            ->addSelect('l')
            ->andWhere('u.id = :id')
            ->setParameter('id', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
