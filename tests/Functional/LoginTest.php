<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testIfLoginIsSuccessful(): void
    {
        // Create a client to simulate a browser
        $client = static::createClient();

        // Get the URL generator service
        $urlGenerator = $client->getContainer()->get('router');
        $userRepository = self::getContainer()->get(UserRepository::class);

        // Find the test user in the database
        $testUser = $userRepository->findOneByEmail('admin@mastercook.ch');
        $this->assertNotNull($testUser, 'Test user not found in the database.');

        // Log in the test user
        $client->loginUser($testUser);

        // User is now logged in, so you can test protected resources
        $client->request('GET', $urlGenerator->generate('recipe.index'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mes recettes');
    }
}
