<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    public function getEntity()
    {
        $recipe = new Recipe();
        $recipe->setDescription('Description 1')
            ->setIsFavorite(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        return $recipe;
    }

    public function testValidName(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $recipe = $this->getEntity();
        $recipe->setName('Name');

        $errors = $container->get('validator')->validate($recipe);

        $this->assertCount(0, $errors);
    }

    public function testInvalidName(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $recipe = $this->getEntity();
        $recipe->setName('');

        $errors = $container->get('validator')->validate($recipe);

        $this->assertCount(2, $errors);
    }

    public function testGetAverage(): void
    {
        $recipe = $this->getEntity();

        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class, 1);

        for ($i = 0; $i < 5; ++$i) {
            $mark = new Mark();
            $mark->setMark(2)
                ->setUser($user)
                ->setRecipe($recipe);

            $recipe->addMark($mark);
        }

        $this->assertTrue(2.0 === $recipe->getAverage());
    }
}
