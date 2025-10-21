<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\RecipeRepository;
use App\State\RecipeProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\RecipeListener'])]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['name', 'user'], entityClass: Recipe::class, groups: ['recipe:item', 'recipe:list'])]
#[ApiResource(
    security: 'ROLE_USER',
    securityMessage: 'Sorry, but you are not the recipe owner.',
    operations: [
        new Delete(security: 'is_granted("ROLE_USER") and object.getUser() == user'),
        new Get(normalizationContext: ['groups' => 'recipe:item']),
        new GetCollection(normalizationContext: ['groups' => 'recipe:list']),
        new Post(normalizationContext: ['groups' => 'recipe:item'], processor: RecipeProcessor::class),
    ],
    // order: ['id' => 'DESC'],
    paginationEnabled: false,
)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:list', 'recipe:item'])]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 50)]
    #[Groups(['recipe:list', 'recipe:item', 'recipe:write'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe:list', 'recipe:item', 'recipe:write'])]
    private ?int $time = null;

    #[Assert\LessThan(51)]
    #[ORM\Column(nullable: true)]
    #[Groups(['recipe:list', 'recipe:item', 'recipe:write'])]
    private ?int $nbPeople = null;

    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'La difficulté doit être compris entre {{ min }} et {{ max }}',
    )]
    #[ORM\Column(nullable: true)]
    #[Groups(['recipe:list', 'recipe:item'])]
    private ?int $difficulty = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Groups(['recipe:list', 'recipe:item', 'recipe:write'])]
    private ?string $description = null;

    #[Assert\GreaterThan(0)]
    #[Assert\LessThan(1000)]
    #[ORM\Column(nullable: true)]
    #[Groups(['recipe:list', 'recipe:item', 'recipe:write'])]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['recipe:list', 'recipe:item', 'recipe:write'])]
    private ?bool $isFavorite = false;

    #[ORM\Column]
    #[Groups(['recipe:list', 'recipe:item', 'recipe:write'])]
    private ?bool $isPublic = false;

    #[ORM\Column]
    #[Assert\NotNull()]
    #[Groups(['recipe:list', 'recipe:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    #[Groups(['recipe:list', 'recipe:item'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class)]
    #[Groups(['recipe:list', 'recipe:item'])]
    private Collection $ingredients;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['recipe:list', 'recipe:item'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Mark::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $marks;

    private ?float $average = null;

    #[Vich\UploadableField(mapping: 'recipe_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[Groups(['recipe:list', 'recipe:item'])]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:list', 'recipe:item'])]
    private ?string $createdBy = null;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->marks = new ArrayCollection();
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getNbPeople(): ?int
    {
        return $this->nbPeople;
    }

    public function setNbPeople(?int $nbPeople): static
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): static
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

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

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection<int, Mark>
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): static
    {
        if (!$this->marks->contains($mark)) {
            $this->marks->add($mark);
            $mark->setRecipe($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): static
    {
        if ($this->marks->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getRecipe() === $this) {
                $mark->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of average.
     */
    public function getAverage()
    {
        $marks = $this->marks;

        if ([] === $marks->toArray()) {
            return 0;
        }

        $total = 0;
        foreach ($marks as $mark) {
            $total += $mark->getMark();
        }

        $this->average = $total / count($marks);

        return $this->average;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function getCategoryName(): ?string
    {
        return $this->category?->getName();
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Set the value of id.
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
