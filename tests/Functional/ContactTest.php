<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testContactFormSubmission(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');
        /** @var \Symfony\Component\Routing\Router $urlGenerator */
        $crawler = $client->request('GET', $urlGenerator->generate('app.contact'));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Contactez-nous');

        // Récuper le formulaire
        $submitButton = $crawler->filter('.btn.btn-primary.mt-4');

        $form = $submitButton->form();
        $form['contact[fullName]'] = 'Jean Dupont';
        $form['contact[email]'] = 'jest@test.com';
        $form['contact[subject]'] = 'Sujet';
        $form['contact[message]'] = 'Message';

        // Soumettre le formulaire
        $client->submit($form);

        // Verifier le status HTTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
