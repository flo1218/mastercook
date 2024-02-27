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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{

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

    #[Route('recipe/public', 'recipe.community', methods:['GET'])]
    public function indexPublic(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ) : Response
    {
        $recipes = $paginator->paginate(
            $repository->findPublicRecipe(),
            $request->query->getInt('page', 1)
        );

        return $this->render('pages/recipe/index_public.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recipes,
        ]);
    }

    #[Route('recipe/show/{id}', 'recipe.show', methods:['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and recipe.isIsPublic() == true || user === recipe.getUser()")]
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
                'recipe' => $recipe
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
            'form' => $form->createView()
        ]);
    }

    /**
     * This function is used to add a new recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recipe/nouveau', name: 'recipe.new', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe, [
            'attr' => [
                'flavor' => 'create'
            ],
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success','Votre recette a été créé avec succès !');

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This function is used to update an existing recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recipe/edition/{id}', name: 'recipe.edit', methods:['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user == recipe.getUser()")]
    public function edit(Request $request,
        Reciperepository $repository, 
        EntityManagerInterface $manager,
        Recipe $recipe) : Response
    {
        $form = $this->createForm(RecipeType::class, $recipe, [
            'attr' => [
                'flavor' => 'edit'
            ]
        ]);

        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success','Votre recette a été modifié avec succès !');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This function is used to delete an existing recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recipe/suppression/{id}', name: 'recipe.delete', methods:['GET'])]
    #[Security("is_granted('ROLE_USER') and user == recipe.getUser()")]
    public function delete(Request $request,
        Reciperepository $repository, 
        EntityManagerInterface $manager,
        Recipe $recipe) : Response
    {
        if (!$recipe) {
            $this->addFlash('warning','Votre recette a n\'a pas été trouvée!');
        } else {
            $manager->remove($recipe);
            $manager->flush();
    
            $this->addFlash('success','Votre recette a été supprimée avec succès !');
        }

        return $this->redirectToRoute('recipe.index');
    }
}
