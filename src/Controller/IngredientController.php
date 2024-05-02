<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class IngredientController extends AbstractController
{
    /**
     * This function displays all ingredients.
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(
        IngredientRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $ingredients = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1)
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * This function is used to add a new ingredients.
     */
    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $ingredient->setUser($this->getUser());
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash('success', 'Votre ingrédient a été créé avec succès !');

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function is used to edit an ingredients.
     */
    #[Route('/ingredient/edition/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    #[IsGranted(
        attribute: new Expression('user === subject && is_granted("ROLE_USER")'),
        subject: new Expression('args["ingredient"].getUser()'),
        message: 'This is not your ingredient'
    )]
    public function edit(
        Ingredient $ingredient,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        if ($ingredient->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Ce n'est pas votre ingredient");
        }

        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash('success', 'Votre ingrédient a été modifié avec succès !');

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function is used to delete an ingredients.
     */
    #[Route('/ingredient/suppression/{id}', name: 'ingredient.delete', methods: ['GET'])]
    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") && user === subject'),
        subject: new Expression('args["ingredient"].getUser()'),
        message: 'This is not your ingredient'
    )]
    public function delete(Request $request,
        EntityManagerInterface $manager,
        Ingredient $ingredient): Response
    {
        if (!$ingredient) {
            $this->addFlash('warning', 'Votre ingrédient a n\'a pas été trouvé !');
        } else {
            $manager->remove($ingredient);
            $manager->flush();
            $this->addFlash('success', 'Votre ingrédient a été supprimé avec succès !');
        }

        return $this->redirectToRoute('ingredient.index');
    }
}
