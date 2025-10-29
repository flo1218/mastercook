<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function getUserCategories(int $userId): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :user')
            ->orWhere('c.is_internal = 1')
            ->setParameter('user', $userId)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function isNameUniquedByUser(string $name, int $userId, ?int $excludedId = null): bool
    {
        $qb = $this->createQueryBuilder('i')
            ->where('i.name = :name')
            ->andWhere('i.user = :userId');

        if ($excludedId) {
            $qb->andWhere('i.id != :excludedId');
            $qb->setParameter('excludedId', $excludedId);
        }

        $qb->setParameter('userId', $userId);
        $qb->setParameter('name', $name);

        return 0 === count($qb->getQuery()->getResult()) ? true : false;
    }
}
