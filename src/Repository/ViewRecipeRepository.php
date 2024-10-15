<?php

namespace App\Repository;

use App\Entity\ViewRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ViewRecipe>
 */
class ViewRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewRecipe::class);
    }

    /**
     * @return ViewRecipe[] Returns an array of ViewRecipe objects
     */
    public function findAllPublicRecipes($limit = 0): array
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.is_public = true')
            ->orderBy('v.id', 'ASC')
        ;

        if (0 != $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return ViewRecipe[] Returns an array of ViewRecipe objects
     */
    public function findAllFavoriteRecipes($userId): array
    {
        return $this->createQueryBuilder('v')
            ->where('v.is_favorite = true')
            ->andWhere('v.user_id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return ViewRecipe[] Returns an array of ViewRecipe objects
     */
    public function findDuplicateRecipe($userId, $name): array
    {
        return $this->createQueryBuilder('v')
            ->where('v.name = :name')
            ->setParameter('name', $name)
            ->andWhere('v.user_id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
    }
}
