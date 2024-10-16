<?php

namespace App\Entity;

use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MarkRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    security: "is_granted('ROLE_USER')",
    operations: [
        new Get(normalizationContext: ['groups' => 'mark:item']),
        new GetCollection(normalizationContext: ['groups' => 'mark:list']),
        new Delete(),
    ],
    //order: ['id' => 'DESC'],
    paginationEnabled: false,
)]
#[ORM\Entity(repositoryClass: MarkRepository::class)]
#[UniqueEntity(
    fields: ['user', 'recipe'],
    errorPath: 'user',
    message: 'Cet utilisateur a déjà noté cette recette'
)]
class Mark
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['mark:list', 'mark:item'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\LessThan(6)]
    #[Groups(['mark:list', 'mark:item'])]
    private ?int $mark = null;

    #[ORM\ManyToOne(inversedBy: 'marks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['mark:list', 'mark:item'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'marks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['mark:list', 'mark:item'])]
    private ?Recipe $recipe = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(int $mark): static
    {
        $this->mark = $mark;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
