<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testIfLoginIsSuccessful(): void
    {
        // Create a client to simulate a browser
        $client = static::createClient();

        // Get the URL generator service
        $urlGenerator = $client->getContainer()->get('router');
        /** @var \Symfony\Component\Routing\Router $urlGenerator */
        /** @var UserRepository $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        // Find the test user in the database
        $testUser = $userRepository->findOneByEmail('admin@mastercook.ch');
        $this->assertNotNull($testUser, 'Test user not found in the database.');

        // Log in the test user
        if ($testUser) {
            $client->loginUser($testUser);
        }

        // User is now logged in, so you can test protected resources
        $client->request('GET', $urlGenerator->generate('recipe.index'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mes recettes');
        // Nouvelles assertions
        $this->assertSelectorExists('nav'); // Vérifie la présence de la barre de navigation
        $this->assertSelectorTextContains('h1', 'Mes recettes'); // Vérifie le titre de la page
    }
}
