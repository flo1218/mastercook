<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Used autoswitch to the right locale
     */
    #[Route('/language', 'home.language', methods: ['GET'])]
    public function language(RecipeRepository $recipeRepository, Request $request): Response
    {
        /** @var User $user **/
        $user = $this->getUser();
        if ($user && $request->getLocale() != strtolower($user->getLanguage())) {
            return $this->redirect('/' . strtolower($user->getLanguage()));
        }

        return $this->redirect('/');
    }

    /**
     * Controller used to display the home page.
     */
    #[Route('/', 'home.index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, Request $request): Response
    {
        return $this->render('pages/home.html.twig', [
            'recipes' => $recipeRepository->findPublicRecipe(20),
        ]);
    }
}
