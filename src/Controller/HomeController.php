<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Controller used to manage the home page.
     */
    #[Route('/', 'home.index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        // $user = $this->getUser();
        return $this->render('pages/home.html.twig', [
            'recipes' => $recipeRepository->findPublicRecipe(20),
        ]);
    }
}
