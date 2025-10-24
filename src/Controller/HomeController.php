<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Cache\CacheItemPoolInterface;

class HomeController extends AbstractController
{
    /**
     * Used autoswitch to the right locale.
     */
    #[Route('/language', 'home.language', methods: ['GET'])]
    public function language(RecipeRepository $recipeRepository, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user && $request->getLocale() != strtolower($user->getLanguage())) {
            return $this->redirect($this->generateUrl('home.index', [
                '_locale' => strtolower($user->getLanguage()),
            ]));
        }

        return $this->redirect($this->generateUrl('home.index', [
            '_locale' => $request->getLocale(),
        ]));
    }

    #[Route('/login/success', 'login.success', methods: ['GET'])]
    public function loginSuccess(): Response
    {
        return $this->redirectToRoute('home.language');
    }

    /**
     * Controller used to display the home page.
     */
    #[Route('/', 'home.index', methods: ['GET'])]
    public function index(RecipeRepository $repository, Request $request, CacheItemPoolInterface $cache): Response
    {
        $item = $cache->getItem('public_recipes');

        if (!$item->isHit()) {
            $data = array_map(function ($recipe) {
                return [
                    'id' => $recipe->getId(),
                    'average' => $recipe->getAverage(),
                    'description' => $recipe->getDescription(),
                    'createdBy' => $recipe->getCreatedBy(),
                    'updatedAt' => $recipe->getUpdatedAt(),
                    'name' => $recipe->getName(),
                    'user' => [
                        'fullName' => $recipe->getUser()->getFullName(),
                        'imageName' => $recipe->getUser()->getImageName(),
                    ],
                ];
            }, $repository->findPublicRecipe());

            $item->set($data);
            $item->expiresAfter(60);
            $cache->save($item);

            $recipes = $data;
        } else {
            $recipes = $item->get();
        }

        $headerDir = $this->getParameter('kernel.project_dir') . '/public/images/header';
        $headerImages = [];
        if (is_dir($headerDir)) {
            $headerImages = array_values(array_filter(scandir($headerDir), function ($file) use ($headerDir) {
                return is_file($headerDir . '/' . $file) && preg_match('/\.(jpe?g|png|gif|webp)$/i', $file);
            }));
        }

        return $this->render('pages/home.html.twig', [
            'recipes' => $recipes,
            'header_images' => $headerImages,
        ]);
    }
}
