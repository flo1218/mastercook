<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class HomeController extends AbstractController
{
    #[Route('/', 'home.index', methods: ['GET'])]
    public function index(
        RecipeRepository $recipeRepository,
        TranslatorInterface $translator,
        Request $request
    ): Response
    {

        $a1 = $request->attributes->get('_locale');
        $locale = $translator->getLocale();

        return $this->render('pages/home.html.twig', [
            'recipes' => $recipeRepository->findPublicRecipe(20)
        ]);
    }
}
