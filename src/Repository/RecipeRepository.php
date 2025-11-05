<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array<int,mixed> $criteria, array<string,string>|null $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array<string,mixed> $criteria, array<string,string>|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function groupByMonth(string $year): mixed
    {
        return $this->createQueryBuilder('r')
            ->select('MONTH(r.createdAt) AS gBmonth, count(r.id) AS gCount')
            ->where('YEAR(r.createdAt) = :year')
            ->orderBy('r.updatedAt', 'DESC')
            ->groupBy('gBmonth')
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Recipe[] Returns an array of Recipe
     */
    public function findPublicRecipe(int $nbRecipes = 0): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->orderBy('r.updatedAt', 'DESC')
            ->setMaxResults($nbRecipes != 0 ? $nbRecipes : null)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Recipe[] Returns an array of Recipe
     */
    public function findFavoriteRecipe(int $nbRecipes = 0, int $userId = 0): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.isFavorite = 1')
            ->andWhere('r.user = :userId')
            ->orderBy('r.id', 'ASC')
            ->setParameter('userId', $userId)
            ->setMaxResults($nbRecipes != 0 ? $nbRecipes : null)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param ArrayCollection<int, Ingredient> $ingredients
     * @param User $user
     * @return Recipe[] Returns an array of Recipe
     */
    public function findRecipesByIngredients(ArrayCollection $ingredients, User $user): array
    {
        $ingredientNames = $ingredients->map(func: fn(Ingredient $ing): string|null => $ing->getName())->toArray();

        if (empty($ingredientNames)) {
            return [];
        }

        // Requête principale pour récupérer les entités (ordonnées par nombre d'ingrédients correspondants)
        $recipes = $this->createQueryBuilder('r')
            ->addSelect('count(i.id) AS HIDDEN ingredients_count')
            ->join('r.ingredients', 'i')
            ->where('i.name IN (:names)')
            ->andWhere('r.isPublic = 1 OR r.user = :user')
            ->setParameter('names', $ingredientNames)
            ->setParameter('user', $user)
            ->groupBy('r.id')
            ->orderBy('ingredients_count', 'DESC')
            ->getQuery()
            ->getResult();

        // Récupérer les ids et calculer les counts (uniquement pour les noms recherchés)
        $ids = array_map(fn(Recipe $r) => $r->getId(), $recipes);

        if (!empty($ids)) {
            $counts = $this->createQueryBuilder('r2')
                ->select('r2.id AS id, COUNT(i2.id) AS cnt')
                ->join('r2.ingredients', 'i2')
                ->where('r2.id IN (:ids)')
                ->andWhere('i2.name IN (:names)')
                ->setParameter('ids', $ids)
                ->setParameter('names', $ingredientNames)
                ->groupBy('r2.id')
                ->getQuery()
                ->getScalarResult();

            $map = [];
            foreach ($counts as $c) {
                $map[(int)$c['id']] = (int)$c['cnt'];
            }

            foreach ($recipes as $recipe) {
                $recipe->setIngredientsCount($map[$recipe->getId()] ?? 0);
            }
        }

        return $recipes;
    }

    /**
     * @param int $userId
     * @param string $name
     * @return Recipe[] Returns an array of Recipe
     */
    public function findDuplicateRecipe(int $userId, string $name): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.name = :name')
            ->setParameter('name', $name)
            ->andWhere('r.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
}
