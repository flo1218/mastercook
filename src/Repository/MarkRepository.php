<?php

namespace App\Repository;

use App\Entity\Mark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mark>
 *
 * @method Mark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mark|null findOneBy(array<string,mixed> $criteria, array<string,string>|null $orderBy = null)
 * @method Mark[]    findAll()
 * @method Mark[]    findBy(array<string,mixed> $criteria, array<string,string>|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
class MarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mark::class);
    }
}
