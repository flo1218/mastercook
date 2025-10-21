<?php

namespace App\Tests\Functional\Api;

use App\Entity\User;
use App\Entity\Recipe;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class RecipeTest extends ApiTestCase
{
    private function getTestRecipe(): Recipe
    {
        $entityManager = self::getEntityManager();

        $recipe = $entityManager->getRepository(Recipe::class)
            ->findOneByName('API-TEST-Recipe');

        return $recipe;
    }

    protected static function getEntityManager()
    {
        return self::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();
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
        $recipe->setCreatedAt(new \DateTimeImmutable(''));
        $recipe->setCreatedBy('Admin');
        $recipe->setUser($admin);
        $entityManager->persist($recipe);

        $entityManager->flush(true);
    }

    public static function tearDownAfterClass(): void
    {
        $entityManager = self::getEntityManager();

        // Remove the test recipes
        $recipeRepository = static::getContainer()->get(RecipeRepository::class);
        $recipe = $recipeRepository->findOneByName('API-TEST-Recipe');
        if ($recipe != null) {
            $entityManager->remove($recipe);
        }

        $recipe = $recipeRepository->findOneByName('recipe-TEST-POST');
        if ($recipe != null) {
            $entityManager->remove($recipe);
        }

        // Remove the test user
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin-test@mastercook.ch');
        if ($user != null) {
            $entityManager->remove($user);
        }

        $entityManager->flush(true);
    }

    protected static function createAuthenticatedClient()
    {
        $client = self::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin-test@mastercook.ch');

        $client->loginUser($testUser);

        return $client;
    }

    /**
     * This function test the POST new recipe from the API
     *
     * @return void
     */
    public function testPostRecipes(): void
    {
        static::createAuthenticatedClient()->request(
            method: 'POST',
            url: '/api/recipes',
            options: [
                'body' => json_encode([
                    "name" => "recipe-TEST-POST",
                    "time" => 1440,
                    "nbPeople" => 50,
                    "difficulty" => 1,
                    "description" => "Description",
                    "price" => 1,
                    "isFavorite" => true,
                    "isPublic" => true
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
                    "time" => 1440,
                    "nbPeople" => 50,
                    "difficulty" => 1,
                    "description" => "Description",
                    "price" => 1,
                    "isFavorite" => true,
                    "isPublic" => true
                ]),
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * This function test the GET ALL Recipes from the API
     *
     * @return void
     */
    public function testGetRecipes(): void
    {
        $client = $this::createAuthenticatedClient();
        $client->request('GET', '/api/recipes');

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('@context', $content);
        $this->assertArrayHasKey('@id', $content);
        $this->assertArrayHasKey('@type', $content);
        $this->assertArrayHasKey('hydra:totalItems', $content);
        $this->assertArrayHasKey('hydra:member', $content);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * This function test the GET of one Recipe from the API
     *
     * @return void
     */
    public function testGetOneRecipe(): void
    {
        $recipe = $this->getTestRecipe();

        $client = $this::createAuthenticatedClient();
        $client->request('GET', "/api/recipes/{$recipe->getId()}");

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('@context', $content);
        $this->assertArrayHasKey('@id', $content);
        $this->assertArrayHasKey('@type', $content);
        $this->assertArrayHasKey('name', $content);

        $this->assertEquals('API-TEST-Recipe', $content['name']);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * This function test the Deletion of a Recipe from the API
     *
     * @return void
     */
    public function testDeleteRecipes(): void
    {
        $recipe = $this->getTestRecipe();
        $client = $this::createAuthenticatedClient();
        $client->request('DELETE', "/api/recipes/{$recipe->getId()}");

        $this->assertResponseIsSuccessful();
    }
}
