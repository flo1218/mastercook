<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');

        //Récuper le formulaire
        $submitButton = $crawler->filter('.btn.btn-primary.mt-4');

        $form = $submitButton->form();    
        $form["contact[fullName]"] = 'Jean Dupont';
        $form["contact[email]"] = 'jest@test.com';
        $form["contact[subject]"] = 'Sujet';
        $form["contact[message]"] = 'Message';

        // Soumettre le formulaire        
        $client->submit($form);

        // Verifier le status HTTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Verifier l'envoi de l'email
        // $this->assertEmailCount(1);
        // $client->followRedirect();

        // // Verifier la présence du message de succès
        // $this->assertSelectorTextContains(
        //     'div.alert.alert-success.mt-4',
        //     'Votre demande a bien été envoyée.'
        // );
    }
}
