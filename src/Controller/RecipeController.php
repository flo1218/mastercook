<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\MarkType;
use App\Form\RecipeType;
use App\Repository\MarkRepository;
use App\Repository\ViewRecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Pontedilana\PhpWeasyPrint\Pdf;
use Pontedilana\WeasyprintBundle\WeasyPrint\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class RecipeController extends AbstractController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly Pdf $weasyPrint,
    ) {
    }

    /**
     * This function is used to display the list of recipes.
     */
    #[Route('/recipe', name: 'recipe.index')]
    #[IsGranted('ROLE_USER')]
    public function index(
        ViewRecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $searchParameters = ['user_id' => $user->getId()];

        $recipeType = $request->query->get('type');
        if ($recipeType) {
            $searchParameters['category_id'] = $recipeType;
        }
        $recettes = $paginator->paginate(
            $repository->findBy($searchParameters),
            $request->query->getInt('page', 1)
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recettes' => $recettes,
            'recipeType' => $recipeType,
        ]);
    }

    /**
     * This function is used to display the list of public recipes.
     */
    #[Route('recipe/public', 'recipe.community', methods: ['GET'])]
    public function indexPublic(
        ViewRecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $cache = new FilesystemAdapter();
        $data = $cache->get('public_recipes', function (ItemInterface $item) use ($repository) {
            $item->expiresAfter(60);

            return $repository->findAllPublicRecipes();
        });

        return $this->render('pages/recipe/index_public.html.twig', [
            'recipes' => $paginator->paginate($data, $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * This function is used to display the list of favorite recipes.
     */
    #[Route('recipe/favorite', 'recipe.favorite', methods: ['GET'])]
    public function indexFavorite(
        ViewRecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
        UserInterface $user
    ): Response {
        /** @var User $user */
        $data = $repository->findAllFavoriteRecipes($user->getId());

        return $this->render('pages/recipe/index_favorite.html.twig', [
            'recettes' => $paginator->paginate($data, $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * This function is used to display the details of a recipe.
     */
    #[Route('recipe/{id}', 'recipe.show', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[IsGranted(
        attribute: new Expression('user === subject["recipe_user"] || subject["recipe_public"]'),
        subject: [
            'recipe_user' => new Expression('args["recipe"].getUser()'),
            'recipe_public' => new Expression('args["recipe"].isIsPublic()'),
        ],
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
        TranslatorInterface $translator,
        Pdf $knpSnappyPdf
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
    public function print(Recipe $recipe)
    {
        $pdfContent = $this->weasyPrint->getOutputFromHtml(
            $this->twig->render('pages/recipe/print.html.twig', [
                'recipe' => $recipe,
            ])
        );

        return new PdfResponse(
            content: $pdfContent,
            fileName: "recipe-{$recipe->getId()}.pdf",
            contentType: 'application/pdf',
            contentDisposition: ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            status: 200,
            headers: []
        );
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
    #[Route('/recipe/favorite/edit/{id}', name: 'recipe.favorite.edit', methods: ['GET', 'POST'])]
    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") && user === subject'),
        subject: new Expression('args["recipe"].getUser()'),
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
        attribute: new Expression('user === subject and is_granted("ROLE_USER")'),
        subject: new Expression('args["recipe"].getUser()'),
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
}
