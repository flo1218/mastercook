<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BasicTest extends WebTestCase
{
    public function testSomething(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('home.index'));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur MasterCook');

        // Nouvelles assertions
        $this->assertSelectorExists('nav'); // Vérifie la présence de la barre de navigation
        $this->assertSelectorTextContains('title', 'MasterCook'); // Vérifie le titre de la page
        $this->assertSelectorExists('footer'); // Vérifie la présence du pied de page
    }
}
