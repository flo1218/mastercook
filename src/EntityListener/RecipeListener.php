<?php

namespace App\EntityListener;

use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class RecipeListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Recipe $recipe): void
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $recipe->setUser($user);
            $fullName = $user->getFullName();
            if (null !== $fullName) {
                $recipe->setCreatedBy($fullName);
            }
        }
    }

    public function preUpdate(Recipe $recipe): void
    {
        $recipe->setUpdatedAt(new \DateTimeImmutable());
    }
}
