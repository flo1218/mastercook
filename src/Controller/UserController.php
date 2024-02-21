<?php

namespace App\Controller;

use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'user.edit')]
    public function edit(UserRepository $repository, 
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordHasherInterface $hasher,
        int $id): Response
    {
        $user = $repository->findOneBy(['id' => $id]);

        if (!$this->getUser() ) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() != $user) {
            return $this->redirectToRoute('recipe.index');
        }


        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
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

    #[Route('/user/edit-password/{id}', name: 'user.edit-password')]
    public function editPassword(UserRepository $repository, 
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordHasherInterface $hasher,
        int $id): Response
    {

        $user = $repository->findOneBy(['id' => $id]);

        if (!$this->getUser() ) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() != $user) {
            return $this->redirectToRoute('recipe.index');
        }

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {

                $user->setPassword($hasher->hashPassword($user, $form->getData()['newPassword']));
                $manager->persist($user);
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
