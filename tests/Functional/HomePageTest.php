<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome to MasterCook!');

        // Check homepage has 5 button (login, register)
        $button = $crawler->filter('.btn');
        $this->assertEquals(5, count($button));
    }
}
