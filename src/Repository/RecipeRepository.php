<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function groupByMonth(string $year)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('MONTH(r.createdAt) AS gBmonth, count(r.id) AS gCount')
            ->where('YEAR(r.createdAt) = :year')
            ->orderBy('r.updatedAt', 'DESC')
            ->groupBy('gBmonth');
        $qb->setParameter('year', $year);

        return $qb->getQuery()->getResult();
    }

    public function findPublicRecipe(int $nbRecipes = 0): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->orderBy('r.updatedAt', 'DESC');

        if (0 != $nbRecipes) {
            $qb->setMaxResults($nbRecipes);
        }

        return $qb->getQuery()->getResult();
    }

    public function findFavoriteRecipe(int $nbRecipes = 0, int $userId = 0): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.isFavorite = 1')
            ->andWhere('r.user = :userId')
            ->orderBy('r.createdAt', 'DESC');

        $qb->setParameter('userId', $userId);

        if (0 != $nbRecipes) {
            $qb->setMaxResults($nbRecipes);
        }

        return $qb->getQuery()->getResult();
    }
}
