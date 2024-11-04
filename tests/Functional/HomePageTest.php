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

        // Check homepage has 4 button (login, register + confirmDeleteModal buttons)
        $button = $crawler->filter('.btn');
        $this->assertEquals(4, count($button));
    }
}
