<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        Request $request
    ): Response {
        $ingredients = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1)
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * This function is used to add a new ingredients.
     * @var User user
     */
    #[Route('/ingredient/new', name: 'ingredient.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
        IngredientRepository $repository,
        UserInterface $user
    ): Response {
        /** @var User $user **/
        if (isset($request->get('ingredient')['cancel'])) {
            return $this->redirectToRoute('ingredient.index');
        }
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            if ($repository->isNameUniquedByUser($ingredient->getName(), $user->getId())) {
                $ingredient->setUser($this->getUser());
                $manager->persist($ingredient);
                $manager->flush();
                $this->addFlash('success', $translator->trans('ingredient.created.label'));
                return $this->redirectToRoute('ingredient.index');
            }
            $this->addFlash('warning', $translator->trans('ingredient.error.notunique.name'));
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function is used to edit an ingredients.
     */
    #[Route('/ingredient/edit/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    #[IsGranted(
        attribute: new Expression('user === subject && is_granted("ROLE_USER")'),
        subject: new Expression('args["ingredient"].getUser()'),
        message: 'Access denied'
    )]
    public function edit(
        Ingredient $ingredient,
        Request $request,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
    ): Response {
        if (isset($request->get('ingredient')['cancel'])) {
            return $this->redirectToRoute('ingredient.index');
        }
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash('success', $translator->trans('ingredient.saved.label'));

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
        message: 'Access denied'
    )]
    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function delete(
        Request $request,
        EntityManagerInterface $manager,
        Ingredient $ingredient
    ): Response {
        if (!$ingredient) {
            $this->addFlash('warning', 'Votre ingrédient n\'a pas été trouvé !');
        } else {
            $manager->remove($ingredient);
            $manager->flush();
            $this->addFlash('success', 'Votre ingrédient a été supprimé avec succès !');
        }

        return $this->redirectToRoute('ingredient.index');
    }
}
