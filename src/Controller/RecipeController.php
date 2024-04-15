<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Form\MarkType;
use App\Form\RecipeType;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\ItemInterface;

class RecipeController extends AbstractController
{
    /**
     * This function is used to display the list of recipes.
     */
    #[Route('/recipe', name: 'recipe.index')]
    #[IsGranted('ROLE_USER')]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1)
        );

        return $this->render('pages/recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recettes' => $recettes,
        ]);
    }

    /**
     * This function is used to display the list of public recipes.
     */
    #[Route('recipe/public', 'recipe.community', methods: ['GET'])]
    public function indexPublic(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $cache = new FilesystemAdapter();
        $data = $cache->get('public_recipes', function (ItemInterface $item) use ($repository) {
            $item->expiresAfter(10);
            return $repository->findPublicRecipe();
        });

        $recipes = $paginator->paginate($data, $request->query->getInt('page', 1));

        return $this->render('pages/recipe/index_public.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recipes,
        ]);
    }

    /**
     * This function is used to display the list of favorite recipes.
     */
    #[Route('recipe/favorite', 'recipe.favorite', methods: ['GET'])]
    public function indexFavorite(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
        UserInterface $user
    ): Response {
        /** @var \App\Entity\User $user * */
        $cache = new FilesystemAdapter();
        $data = $cache->get('favorite_recipes', function (ItemInterface $item) use ($repository, $user) {
            $item->expiresAfter(10);
            return $repository->findFavoriteRecipe(0, $user->getId());
        });

        $recettes = $paginator->paginate($data, $request->query->getInt('page', 1));

        return $this->render('pages/recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recettes' => $recettes,
        ]);
    }

    /**
     * This function is used to display the details of a recipe.
     */
    #[Route('recipe/{id}', 'recipe.show', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") && (user === subject["recipe_user"] || subject["recipe_public"] )'),
        subject: [
            'recipe_user' => new Expression('args["recipe"].getUser()'),
            'recipe_public' => new Expression('args["recipe"].isIsPublic()'),
        ],
        message: 'This is not your recipe'
    )]
    public function show(Recipe $recipe, Request $request, MarkRepository $markRepository, EntityManagerInterface $manager)
    {
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
                $existingMark->setMark(
                    $form->getData()->getMark()
                );
            }
            $manager->flush();
            $this->addFlash('success', 'Votre note a bien été prise en compte');
            return $this->redirectToRoute('recipe.show', ['id' => $recipe->getId()]);
        }

        return $this->render('pages/recipe/show.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function is used to add a new recipe.
     */
    #[Route('/recipe/nouveau', 'recipe.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe, [
            'attr' => [
                'flavor' => 'create',
            ],
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success', 'Votre recette a été créé avec succès !');
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
    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") && user === subject'),
        subject: new Expression('args["recipe"].getUser()'),
    )]
    public function edit(
        Request $request,
        RecipeRepository $repository,
        EntityManagerInterface $manager,
        Recipe $recipe
    ): Response {
        if ($recipe->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Ce n'est pas votre recette");
        }

        $form = $this->createForm(RecipeType::class, $recipe, [
            'attr' => [
                'flavor' => 'edit',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash('success', 'Votre recette a été modifié avec succès !');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView(),
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
    public function delete(Request $request,
        RecipeRepository $repository,
        EntityManagerInterface $manager,
        Recipe $recipe): Response
    {
        if (!$recipe) {
            $this->addFlash('warning', 'Votre recette a n\'a pas été trouvée!');
        } else {
            $manager->remove($recipe);
            $manager->flush();
            $this->addFlash('success', 'Votre recette a été supprimée avec succès !');
        }

        return $this->redirectToRoute('recipe.index');
    }
}
