<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $testUser = $userRepository->findOneByEmail('admin@mastercook.ch');
        $createdTestUser = null === $testUser;

        if ($createdTestUser) {
            $testUser = (new User())
                ->setFullName('Admin')
                ->setPseudo('Admin')
                ->setLanguage('fr')
                ->setEmail('admin@mastercook.ch')
                ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
                ->setPlainPassword('password');
            $entityManager->persist($testUser);
            $entityManager->flush();
        }

        try {
            $client->loginUser($testUser);
            $client->request('GET', $urlGenerator->generate('recipe.index'));

            $this->assertResponseIsSuccessful();
            $this->assertSelectorTextContains('h1', 'Mes recettes');
            $this->assertSelectorExists('nav');
        } finally {
            if ($createdTestUser) {
                $entityManager->remove($testUser);
                $entityManager->flush();
            }
        }
    }
}
