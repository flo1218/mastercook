<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testIfLoginIsSuccessful(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('app_login'));

        // Form
        $form = $crawler->filter('form[name=login]')->form([
            '_username' => 'admin@mastercook.ch',
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();
    }
}
