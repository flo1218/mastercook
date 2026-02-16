<?php

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class RecipeTest extends ApiTestCase
{
    private function getTestRecipe(): ?Recipe
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getEntityManager();

        $recipeRepository = $entityManager->getRepository(Recipe::class);

        return $recipeRepository->findOneByName('API-TEST-Recipe');
    }

    protected static function getEntityManager(): EntityManagerInterface
    {
        /** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
        $doctrine = self::bootKernel()->getContainer()->get('doctrine');
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $doctrine->getManager();

        return $entityManager;
    }

    public static function setUpBeforeClass(): void
    {
        $entityManager = self::getEntityManager();

        // Create a test user
        $admin = new User();
        $admin->setFullName('Admin')
            ->setPseudo('Admin')
            ->setLanguage('fr')
            ->setEmail('admin-test@mastercook.ch')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');

        $entityManager->persist($admin);

        // Create a test Recipe
        $recipe = new Recipe();
        $recipe->setName('API-TEST-Recipe');
        $recipe->setDescription('desc');
        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setCreatedBy('Admin');
        $recipe->setUser($admin);
        $entityManager->persist($recipe);

        $entityManager->flush();
    }

    public static function tearDownAfterClass(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getEntityManager();

        // Remove the test recipes
        /** @var RecipeRepository $recipeRepository */
        $recipeRepository = static::getContainer()->get(RecipeRepository::class);
        $recipe = $recipeRepository->findOneByName('API-TEST-Recipe');
        if (null !== $recipe) {
            $entityManager->remove($recipe);
        }

        $recipe = $recipeRepository->findOneByName('recipe-TEST-POST');
        if (null !== $recipe) {
            $entityManager->remove($recipe);
        }

        // Remove the test user
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin-test@mastercook.ch');
        if (null !== $user) {
            $entityManager->remove($user);
        }

        $entityManager->flush();
    }

    protected static function createAuthenticatedClient(): Client
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin-test@mastercook.ch');

        if ($testUser) {
            $client->loginUser($testUser);
        }

        return $client;
    }

    /**
     * This function test the POST new recipe from the API.
     */
    public function testPostRecipes(): void
    {
        static::createAuthenticatedClient()->request(
            method: 'POST',
            url: '/api/recipes',
            options: [
                'body' => json_encode([
                    'name' => 'recipe-TEST-POST',
                    'time' => 1440,
                    'nbPeople' => 50,
                    'difficulty' => 1,
                    'description' => 'Description',
                    'price' => 1,
                    'isFavorite' => true,
                    'isPublic' => true,
                ]),
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                ],
            ],
        );

        $this->assertResponseIsSuccessful();
    }

    public function testPostRecipesFailed(): void
    {
        static::createAuthenticatedClient()->request(
            method: 'POST',
            url: '/api/recipes',
            options: [
                'body' => json_encode([
                    'time' => 1440,
                    'nbPeople' => 50,
                    'difficulty' => 1,
                    'description' => 'Description',
                    'price' => 1,
                    'isFavorite' => true,
                    'isPublic' => true,
                ]),
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * This function test the GET ALL Recipes from the API.
     */
    public function testGetRecipes(): void
    {
        $client = $this::createAuthenticatedClient();
        $client->request('GET', '/api/recipes');

        $response = $client->getResponse();
        if (null === $response) {
            $this->fail('Response is null');
        }
        $content = json_decode($response->getContent(), true);

        $this->assertIsArray($content);
        $this->assertArrayHasKey('@context', $content);
        $this->assertArrayHasKey('@id', $content);
        $this->assertArrayHasKey('@type', $content);
        $this->assertArrayHasKey('totalItems', $content);
        $this->assertArrayHasKey('member', $content);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * This function test the GET of one Recipe from the API.
     */
    public function testGetOneRecipe(): void
    {
        $recipe = $this->getTestRecipe();
        if (null === $recipe) {
            $this->fail('Test recipe not found');
        }

        $client = $this::createAuthenticatedClient();
        $client->request('GET', "/api/recipes/{$recipe->getId()}");

        $response = $client->getResponse();
        if (null === $response) {
            $this->fail('Response is null');
        }
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
        $this->assertArrayHasKey('@context', $content);
        $this->assertArrayHasKey('@id', $content);
        $this->assertArrayHasKey('@type', $content);
        $this->assertArrayHasKey('name', $content);

        $this->assertEquals('API-TEST-Recipe', $content['name'] ?? '');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * This function test the Deletion of a Recipe from the API.
     */
    public function testDeleteRecipes(): void
    {
        $recipe = $this->getTestRecipe();
        if (null === $recipe) {
            $this->fail('Test recipe not found');
        }

        $client = $this::createAuthenticatedClient();
        $client->request('DELETE', "/api/recipes/{$recipe->getId()}");

        $this->assertResponseIsSuccessful();
    }
}
