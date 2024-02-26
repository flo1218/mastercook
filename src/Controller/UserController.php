<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'user_edit')]
    #[Security("is_granted('ROLE_USER') and user == choosenUser")]
    public function edit(
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordHasherInterface $hasher,
        User $choosenUser): Response
    {
        if (!$this->getUser() ) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() != $choosenUser) {
            return $this->redirectToRoute('recipe.index');
        }


        $form = $this->createForm(UserType::class, $choosenUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($hasher->isPasswordValid($choosenUser, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();
    
                $this->addFlash('success','Votre utilisateur a été modifié avec succès.');
    
                return $this->redirectToRoute('recipe.index');
            } else {
                $this->addFlash('warning','le mot de passe renseigné est incorrect.');
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/edit-password/{id}', name: 'user_edit-password')]
    #[Security("is_granted('ROLE_USER') and user == choosenUser")]
    public function editPassword(
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordHasherInterface $hasher,
        User $choosenUser): Response
    {
        if (!$this->getUser() ) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() != $choosenUser) {
            return $this->redirectToRoute('recipe.index');
        }

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($choosenUser, $form->getData()['plainPassword'])) {

                $choosenUser->setPassword($hasher->hashPassword($choosenUser, $form->getData()['newPassword']));
                $manager->persist($choosenUser);
                $manager->flush();
    
                $this->addFlash('success','Votre mot de passe a été modifié avec succès.');
    
                return $this->redirectToRoute('recipe.index');
            } else {
                $this->addFlash('warning','le mot de passe renseigné est incorrect.');
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
