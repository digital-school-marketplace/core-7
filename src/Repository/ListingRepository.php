<?php

namespace App\Repository;

use App\Entity\Listing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ListingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listing::class);
    }


    /**
     * Filter listings by category
     */
    public function findByCategoryName(string $categoryName): array
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.category', 'c')
            ->addSelect('c')
            ->andWhere('c.name = :name')
            ->setParameter('name', $categoryName)
            ->getQuery()
            ->getResult();
    }

    /**
     * Sort listings by price
     */
    public function findAllSortedByPrice(string $order = 'ASC'): array
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.price', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Pagination
     */
    public function findPaginated(int $page = 1, int $limit = 10): array
    {
        return $this->createQueryBuilder('l')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Complex query: filter by category and sort by price
     */
    public function findFiltered(
        ?int $categoryId = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        string $sortBy = 'newest',
        int $page = 1,
        int $limit = 20,
    ): array {
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.category', 'c')->addSelect('c')
            ->leftJoin('l.user', 'u')->addSelect('u');

        if ($categoryId !== null) {
            $qb->andWhere('c.id = :category')
                ->setParameter('category', $categoryId);
        }

        if ($minPrice !== null) {
            $qb->andWhere('l.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('l.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        match ($sortBy) {
            'price_asc'  => $qb->orderBy('l.price', 'ASC'),
            'price_desc' => $qb->orderBy('l.price', 'DESC'),
            'oldest'     => $qb->orderBy('l.id', 'ASC'),
            default      => $qb->orderBy('l.id', 'DESC'),
        };

        return $qb
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
