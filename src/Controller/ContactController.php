<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * Controller used to manage the contact page.
     *
     * @var User user
     */
    #[Route(path: [
        'en' => '/contact-us',
        'fr' => '/contactez-nous'
    ], name: 'app.contact')]
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        MailService $mailService
    ): Response {
        $contact = new Contact();

        // Prefill fields if user is connected
        if ($this->getUser()) {
            /** @var User $user **/
            $user = $this->getUser();
            $contact->setFullName($user->getFullName());
            $contact->setEmail($user->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            // Send email
            $mailService->sendMail(
                $contact->getEmail(),
                $contact->getSubject(),
                ['contact' => $contact]
            );

            $this->addFlash('success', 'contact.success.message');
        }

        return $this->render('pages/contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }
}
