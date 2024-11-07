<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
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
