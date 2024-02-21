<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{

    #[Route('/recipe', name: 'recipe.index')]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recettes' => $recettes,
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
    public function edit(Request $request,
        Reciperepository $repository, 
        EntityManagerInterface $manager,
        int $id) : Response
    {
        $recipe = $repository->findOneBy(['id' => $id]);

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
    public function delete(Request $request,
        Reciperepository $repository, 
        EntityManagerInterface $manager,
        int $id) : Response
    {

        $recipe = $repository->findOneBy(['id' => $id]);
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
