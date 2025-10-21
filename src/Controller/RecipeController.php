<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Recipe;
use App\Form\MarkType;
use App\Form\RecipeType;
use App\Form\RecipeSearchType;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;

class RecipeController extends AbstractController
{
    /**
     * This function is used to display the list of recipes.
     */
    #[Route('/recipe', name: 'recipe.index')]
    #[IsGranted('ROLE_USER')]
    public function index(
        RecipeRepository $repository,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $searchParameters = ['user' => $user->getId()];

        $recipeType = $request->query->get('type');
        if ($recipeType) {
            $searchParameters['category'] = $recipeType;
        }
        $recettes = $paginator->paginate(
            $repository->findBy($searchParameters),
            $request->query->getInt('page', 1)
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recettes' => $recettes,
            'recipeType' => $recipeType,
            'categories' => $categoryRepository->getUserCategories($user->getId()),
        ]);
    }

    /**
     * This function is used to display the list of public recipes.
     */
    #[Route('public-recipe/', 'recipe.community', methods: ['GET'])]
    public function indexPublic(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $cache = new FilesystemAdapter();
        $data = $cache->get('public_recipes', function (ItemInterface $item) use ($repository) {
            $item->expiresAfter(60);
            return array_map(function ($recipe) {
                return [
                    'id' => $recipe->getId(),
                    'average' => $recipe->getAverage(),
                    'description' => $recipe->getDescription(),
                    'createdBy' => $recipe->getCreatedBy(),
                    'updatedAt' => $recipe->getUpdatedAt(),
                    'name' => $recipe->getName(),
                    'imageName' => $recipe->getImageName(),
                    'user' => [
                        'fullName' => $recipe->getUser()->getFullName(),
                        'imageName' => $recipe->getUser()->getImageName(),
                    ],
                ];
            }, $repository->findPublicRecipe());
        });

        return $this->render('pages/recipe/index_public.html.twig', [
            'recipes' => $paginator->paginate($data, $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * This function is used to display the list of favorite recipes.
     */
    #[Route('favorite-recipe', 'recipe.favorite', methods: ['GET'])]
    public function indexFavorite(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
        UserInterface $user
    ): Response {
        /** @var User $user */
        $data = $repository->findFavoriteRecipe(0, $user->getId());

        return $this->render('pages/recipe/index_favorite.html.twig', [
            'recettes' => $paginator->paginate($data, $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * This function is used to display the details of a recipe.
     */
    #[Route('recipe/{id}', 'recipe.show', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[IsGranted(
        attribute: new Expression('user == subject.getUser() && is_granted("ROLE_USER")'),
        subject: 'recipe',
        message: 'Access denied'
    )]
    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function show(
        Recipe $recipe,
        Request $request,
        MarkRepository $markRepository,
        EntityManagerInterface $manager,
        TranslatorInterface $translator
    ) {
        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mark->setuser($this->getUser())
                ->setRecipe($recipe);

            $existingMark = $markRepository->findOneBy([
                'user' => $this->getUser(),
                'recipe' => $recipe,
            ]);

            if (!$existingMark) {
                $manager->persist($mark);
            } else {
                $existingMark->setMark($form->getData()->getMark());
            }
            $manager->flush();
            $this->addFlash('success', $translator->trans('recipe.vote.success'));

            // Invalid cache
            $cache = new FilesystemAdapter();
            $cache->delete('public_recipes');

            return $this->redirectToRoute('recipe.show', ['id' => $recipe->getId()]);
        }

        return $this->render('pages/recipe/show.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('recipe/print/{id}', 'recipe.print', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function print(Recipe $recipe, DompdfWrapperInterface $wrapper)
    {
        $pdfContent = $this->renderView('pages/recipe/print.html.twig', [
            'recipe' => $recipe,
        ]);

        $response = $wrapper->getStreamResponse(
            $pdfContent,
            "recipe_{$recipe->getId()}.pdf",   // filename
            [
                'isRemoteEnabled' => false, // allow external images
                'defaultFont' => 'Arial'
            ]
        );

        $response->headers->set('Content-Disposition', 'inline; filename="document.pdf"');

        return $response;
    }

    /**
     * This function is used to add a new recipe.
     */
    #[Route('/recipe/new', 'recipe.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
    ): Response {
        if (isset($request->get('recipe')['cancel'])) {
            return $this->redirectToRoute('recipe.index');
        }

        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success', $translator->trans('recipe.created.label'));

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function is used to update an existing recipe.
     */
    #[Route('/recipe/edit/{id}', name: 'recipe.edit', methods: ['GET', 'POST'])]
    #[Route('/favorite-recipe/edit/{id}', name: 'recipe.favorite.edit', methods: ['GET', 'POST'])]
    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") && user == subject.getUser()'),
        subject: 'recipe',
        message: 'Access denied'
    )]
    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        Recipe $recipe,
        TranslatorInterface $translator,
    ): Response {
        if (isset($request->get('recipe')['cancel'])) {
            return $this->redirectToRoute('recipe.index');
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash('success', $translator->trans('recipe.updated.label'));

            if ($request->query->get('redirect')) {
                return $this->redirect($request->query->get('redirect'));
            }

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    /**
     * This function is used to delete an existing recipe.
     */
    #[Route('/recipe/suppression/{id}', name: 'recipe.delete', methods: ['GET'])]
    #[IsGranted(
        attribute: new Expression('user === subject.getUser() && is_granted("ROLE_USER")'),
        subject: 'recipe',
    )]
    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function delete(
        Request $request,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
        Recipe $recipe
    ): Response {
        if (!$recipe) {
            $this->addFlash('warning', $translator->trans('recipe.notfound.label'));
        } else {
            $manager->remove($recipe);
            $manager->flush();
            $this->addFlash('success', $translator->trans('recipe.deleted.label'));
        }

        if ($request->query->get('redirect')) {
            return $this->redirect($request->query->get('redirect'));
        }

        return $this->redirectToRoute('recipe.index');
    }

    #[Route('suggest-recipe/', 'recipe.suggest', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function suggest(
        Request $request,
        EntityManagerInterface $manager,
        PaginatorInterface $paginator,
        IngredientRepository $repository,
        RecipeRepository $recipeRepo,
        TranslatorInterface $translator
    ): Response {
        if (isset($request->get('recipe_search')['cancel'])) {
            return $this->redirectToRoute('recipe.suggest');
        }

        $recipes = null;
        $form = $this->createForm(RecipeSearchType::class, null);
        $form->handleRequest($request);
        $ingredients = $form->getData();
        if ($form->isSubmitted() && count($ingredients['ingredients']) > 0) {
            $user = $this->getUser();
            $recipes = $recipeRepo->findRecipesByIngredients($ingredients['ingredients'], $user);
        }

        $ingredients = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1)
        );

        return $this->render('pages/recipe/suggest.html.twig', [
            'form' => $form->createView(),
            'ingredients' => $ingredients,
            'recettes' => $recipes,
        ]);
    }
}
