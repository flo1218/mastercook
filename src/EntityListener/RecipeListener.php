<?php

namespace App\EntityListener;

use App\Entity\Recipe;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class RecipeListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Recipe $recipe, LifecycleEventArgs $event): void
    {
        if ($user = $this->security->getUser()) {
            /*
             * @var \App\Entity\User $user
             */
            $recipe->setUser($this->security->getUser());
            $recipe->setCreatedBy($user->getFullName());
        }
    }

    public function preUpdate(Recipe $recipe, LifecycleEventArgs $event)
    {
        $recipe->setUpdatedAt(new \DateTimeImmutable());
    }
}
