<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Repository\ViewRecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

class HomeController extends AbstractController
{
    /**
     * Used autoswitch to the right locale.
     */
    #[Route('/language', 'home.language', methods: ['GET'])]
    public function language(RecipeRepository $recipeRepository, Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if ($user && $request->getLocale() != strtolower($user->getLanguage())) {
            return $this->redirect('/' . strtolower($user->getLanguage()));
        }

        return $this->redirect('/' . $request->getLocale());
    }

    /**
     * Controller used to display the home page.
     */
    #[Route('/', 'home.index', methods: ['GET'])]
    public function index(ViewRecipeRepository $recipeRepository, Request $request): Response
    {
        $cache = new FilesystemAdapter();
        $data = $cache->get('public_recipes', function (ItemInterface $item) use ($recipeRepository) {
            $item->expiresAfter(60);

            return $recipeRepository->findAllPublicRecipes(20);
        });

        return $this->render('pages/home.html.twig', [
            'recipes' => $data,
        ]);
    }
}
