<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome to MasterCook!');

        // Check homepage has 3 cards displaying public recipes
        $recipes = $crawler->filter('.card');
        $this->assertEquals(20, count($recipes));

        // Check homepage has 2 button (login, register)
        $button = $crawler->filter('.btn');
        $this->assertEquals(2, count($button));
    }
}
