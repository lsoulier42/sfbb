<?php

namespace App\Controller\Admin;

use App\Contract\Service\CategoryServiceInterface;
use App\Entity\Category;
use App\Enum\ChangeOrderEnum;
use App\Form\Category\CategoryType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/category'), IsGranted('ROLE_ADMIN')]
class CategoryAdminController extends AbstractController
{
    #[Route(path: '/', name: 'category_index', methods: ['GET'])]
    public function index(CategoryServiceInterface $categoryService): Response
    {
        $categories = $categoryService->listAll();
        return $this->render(
            'admin/category/index.html.twig',
            [
                'categories' => $categories
            ]
        );
    }

    #[Route(path: '/new', name: 'category_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CategoryServiceInterface $categoryService
    ): Response|RedirectResponse {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $categoryService->createNewCategory($category);
                $this->addFlash('success', 'category.success.create');
                return $this->redirectToRoute('category_edit', ['category' => $category->getId()]);
            } catch (Exception $exception) {
                $this->addFlash('danger', $exception->getMessage());
            }
            return $this->redirectToRoute('category_index');
        }
        return $this->render(
            'admin/category/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route(path: '/{category}/edit', name: 'category_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Category $category,
        CategoryServiceInterface $categoryService
    ): Response|RedirectResponse {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $categoryService->editCategory($category);
                $this->addFlash('success', 'category.success.edit');
                return $this->redirectToRoute('category_edit', ['category' => $category->getId()]);
            } catch (Exception $exception) {
                $this->addFlash('danger', $exception->getMessage());
            }
            return $this->redirectToRoute('category_index');
        }
        return $this->render(
            'admin/category/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route(path: '/{category}/delete', name: 'category_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Category $category,
        CategoryServiceInterface $categoryService
    ): JsonResponse {
        if (!$this->isCsrfTokenValid('delete-category', (string)$request->request->get('token'))) {
            return new JsonResponse('global.error.csrf.invalid', 500);
        }
        try {
            $categoryService->deleteCategory($category);
            $this->addFlash('success', 'category.success.delete');
            return new JsonResponse("OK", 200);
        } catch (Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());
            return new JsonResponse($exception->getMessage(), 500);
        }
    }

    #[Route(
        path: '/{category}/change-order/{direction}',
        name: 'category_change_order',
        requirements: ['direction' => '^(up|down)$'],
        methods: ['GET']
    )]
    public function changeOrder(
        Category $category,
        string $direction,
        CategoryServiceInterface $categoryService
    ): RedirectResponse {
        try {
            $changeOrderDirection = ChangeOrderEnum::from($direction);
            $categoryService->changeOrder($category, $changeOrderDirection);
            $this->addFlash('success', 'category.success.change_order');
        } catch (Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->redirectToRoute('category_index');
    }
}
