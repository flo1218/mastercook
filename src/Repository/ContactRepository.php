<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array<string,mixed> $criteria, array<string,string>|null $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array<string,mixed> $criteria, array<string,string>|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }
}
