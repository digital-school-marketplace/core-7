<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Find categories sorted alphabetically
     */
    public function findAllSorted(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Pagination for categories
     */
    public function findPaginated(int $page = 1, int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find categories with listings (optimized query)
     */
    public function findWithListings(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.listings', 'l')
            ->addSelect('l')
            ->getQuery()
            ->getResult();
    }
}
