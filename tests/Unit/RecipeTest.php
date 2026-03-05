<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecipeTest extends KernelTestCase
{

    protected static function getEntityManager(): EntityManagerInterface
    {
        /** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
        $doctrine = self::bootKernel()->getContainer()->get('doctrine');
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $doctrine->getManager();

        return $entityManager;
    }

    public static function tearDownAfterClass(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getEntityManager();

        // Remove the test user
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('test@example.com');
        if (null !== $user) {
            $entityManager->remove($user);
        }

        $entityManager->flush();
    }

    public function getEntity(): Recipe
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

        /** @var ValidatorInterface $validator */
        $validator = $container->get('validator');
        $errors = $validator->validate($recipe);

        $this->assertCount(0, $errors);
    }

    public function testInvalidName(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $recipe = $this->getEntity();
        $recipe->setName('');

        /** @var ValidatorInterface $validator */
        $validator = $container->get('validator');
        $errors = $validator->validate($recipe);

        $this->assertCount(1, $errors);
    }

    public function testGetAverage(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $recipe = $this->getEntity();

        $user = $entityManager->find(User::class, 1);
        if (!$user) {
            $user = new User();
            $user->setEmail('test@example.com');
            $entityManager->persist($user);
            $entityManager->flush();
        }

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
