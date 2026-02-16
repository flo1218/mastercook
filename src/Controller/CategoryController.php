<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category.index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(
        CategoryRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $user = $this->getUser();
        $userId = $user instanceof User ? $user->getId() : 0;
        $userCategories = $repository->findBy(['user' => $userId]);
        $internalCategories = $repository->findBy(['is_internal' => 1]);

        // Fusionner les catégories internes et celles de l'utilisateur
        $allCategories = array_merge($internalCategories, $userCategories);

        $categories = $paginator->paginate(
            $allCategories,
            $request->query->getInt('page', 1)
        );

        return $this->render('pages/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * This function is used to add a new Categorys.
     */
    #[Route('/category/new', name: 'category.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
        CategoryRepository $repository,
        User $user,
    ): Response {
        $categoryData = $request->get('category');
        if (is_array($categoryData) && isset($categoryData['cancel'])) {
            return $this->redirectToRoute('category.index');
        }
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            if ($category instanceof Category) {
                $categoryName = $category->getName();
                $userId = $user->getId();
                if (null !== $categoryName && null !== $userId && $repository->isNameUniquedByUser($categoryName, $userId)) {
                    $category->setUser($this->getUser() instanceof User ? $this->getUser() : null);
                    $category->setIsInternal(false);
                    $manager->persist($category);
                    $manager->flush();
                    $this->addFlash('success', $translator->trans('category.created.label'));

                    return $this->redirectToRoute('category.index');
                }
            }
            $this->addFlash('warning', $translator->trans('category.error.notunique.name'));
        }

        return $this->render('pages/category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function is used to edit an Categorys.
     */
    #[Route('/category/edit/{id}', name: 'category.edit', methods: ['GET', 'POST'])]
    #[IsGranted(
        attribute: new Expression('user == subject.getUser() && is_granted("ROLE_USER")'),
        subject: 'category',
        message: 'Access denied'
    )]
    public function edit(
        Category $category,
        Request $request,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
        CategoryRepository $repository,
        User $user,
    ): Response {
        $categoryData = $request->get('category');
        if (is_array($categoryData) && isset($categoryData['cancel'])) {
            return $this->redirectToRoute('category.index');
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            if ($category instanceof Category) {
                $categoryName = $category->getName();
                $categoryId = $category->getId();
                $userId = $user->getId();
                if (null !== $categoryName && null !== $userId && $repository->isNameUniquedByUser($categoryName, $userId, $categoryId)) {
                    $manager->persist($category);
                    $manager->flush();
                    $this->addFlash('success', $translator->trans('category.saved.label'));

                    return $this->redirectToRoute('category.index');
                }
            }
            $this->addFlash('warning', $translator->trans('category.error.notunique.name'));
        }

        return $this->render('pages/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function is used to delete an Categorys.
     */
    #[Route('/category/suppression/{id}', name: 'category.delete', methods: ['GET'])]
    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") && user === subject.getUser()'),
        subject: 'category',
        message: 'Access denied'
    )]
    public function delete(
        Request $request,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
        Category $category,
    ): Response {
        $manager->remove($category);
        $manager->flush();
        $this->addFlash('success', $translator->trans('category.deleted.label'));

        return $this->redirectToRoute('category.index');
    }
}
