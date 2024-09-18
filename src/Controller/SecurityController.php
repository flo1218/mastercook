<?php

namespace App\Controller;

use App\Entity\Category;
use LogicException;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * This function manage the connexion process.
     */
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('error', $error);
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * This function manage the logout process.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('It will be intercepted by the logout key on your firewall.');
    }

    /**
     * This function manage the registration process.
     */
    #[Route(path: '/inscription', name: 'app_register')]
    public function registration(
        Request $request,
        EntityManagerInterface $manager,
        TranslatorInterface $translator
    ): Response {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);

            // Add default categories
            $locale = strtoupper($user->getLanguage()) == 'FR' ? 'fr_FR' : 'en_EN';
            $defaultCategories = [
                $translator->trans('category.default.starter', [], null, $locale),
                $translator->trans('category.default.main', [], null, $locale),
                $translator->trans('category.default.dessert', [], null, $locale),
            ];

            foreach ($defaultCategories as $defaultCategory) {
                $category = new Category();
                $category->setUser($user)
                    ->setName($defaultCategory);
                $manager->persist($category);
            }
            $manager->flush();

            $this->addFlash('success', $translator->trans('registration.success.label'));

            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
