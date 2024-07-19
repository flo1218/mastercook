<?php

namespace App\Entity;

use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\IngredientRepository;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[UniqueEntity(['name', 'user'])]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    operations: [
        new Get(normalizationContext: ['groups' => 'ingredient:item']),
        new GetCollection(normalizationContext: ['groups' => 'ingredient:list']),
        new Delete(),
    ],
    order: ['id' => 'DESC'],
    paginationEnabled: false,
)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ingredient:list', 'ingredient:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit avoir une longueur de {{ limit }} caractères au minimum',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )
    ]
    #[Groups(['ingredient:list', 'ingredient:item'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    #[Assert\LessThan(200)]
    private ?float $price = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    #[Groups(['ingredient:list', 'ingredient:item'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?User $user = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
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
}
