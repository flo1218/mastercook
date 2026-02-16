<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'user_edit')]
    #[IsGranted(
        attribute: new Expression('user == subject and is_granted("ROLE_USER")'),
        subject: 'user',
        message: 'Access denied'
    )]
    public function edit(
        EntityManagerInterface $manager,
        Request $request,
        TranslatorInterface $translator,
        User $user,
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
            if ($userData instanceof User) {
                $manager->persist($userData);
                $manager->flush();
                $this->addFlash('success', $translator->trans('registration.update.label'));

                return $this->redirectToRoute('recipe.index');
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/edit-password/{id}', name: 'user_edit-password')]
    #[IsGranted(
        attribute: new Expression('user == subject and is_granted("ROLE_USER")'),
        subject: 'user',
        message: 'Access denied'
    )]
    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function editPassword(
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordHasherInterface $hasher,
        User $user,
    ): Response {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $plain = is_array($data) && isset($data['plainPassword']) && is_string($data['plainPassword']) ? $data['plainPassword'] : '';
            $new = is_array($data) && isset($data['newPassword']) && is_string($data['newPassword']) ? $data['newPassword'] : '';
            if ('' !== $plain && $hasher->isPasswordValid($user, $plain)) {
                if ('' !== $new) {
                    $user->setPassword($hasher->hashPassword($user, $new));
                    $manager->persist($user);
                    $manager->flush();

                    $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');

                    return $this->redirectToRoute('recipe.index');
                }
            } else {
                $this->addFlash('warning', 'le mot de passe renseigné est incorrect.');
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
