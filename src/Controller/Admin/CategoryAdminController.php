<?php

namespace App\Controller\Admin;

use App\Contract\Service\CategoryServiceInterface;
use App\Controller\BaseController;
use App\Entity\Category;
use App\Enum\ChangeOrderEnum;
use App\Form\Category\CategoryType;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/category'), IsGranted('ROLE_ADMIN')]
class CategoryAdminController extends BaseController
{
    #[Route(path: '/', name: 'admin_category_index', methods: ['GET'])]
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

    #[Route(path: '/new', name: 'admin_category_new', methods: ['GET', 'POST'])]
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
                $this->addSuccessMessage(
                    'category.success.new',
                    ['%title%' => $category->getTitle()]
                );
                return $this->redirectToRoute(
                    'admin_category_edit',
                    ['category' => $category->getId()]
                );
            } catch (Exception $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render(
            'admin/category/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route(path: '/{category}/edit', name: 'admin_category_edit', methods: ['GET', 'POST'])]
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
                $this->addSuccessMessage('category.success.edit', ['%title%' => $category->getTitle()]);
                return $this->redirectToRoute('admin_category_edit', ['category' => $category->getId()]);
            } catch (Exception $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render(
            'admin/category/edit.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category
            ]
        );
    }

    #[Route(path: '/{category}/delete', name: 'admin_category_delete', methods: ['POST'])]
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
            $this->addSuccessMessage(
                'category.success.delete',
                ['%title%' => $category->getTitle()]
            );
            return new JsonResponse("OK", 200);
        } catch (Exception $exception) {
            $this->addErrorMessage($exception->getMessage());
            return new JsonResponse($exception->getMessage(), 500);
        }
    }

    #[Route(
        path: '/{category}/change-order/{direction}',
        name: 'admin_category_change_order',
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
            $directionTranslationKey = $changeOrderDirection === ChangeOrderEnum::UP
                ? 'category.success.change_order.up'
                : 'category.success.change_order.down';
            $directionMessage = $this->translator->trans($directionTranslationKey);
            $this->addSuccessMessage(
                'category.success.change_order.message',
                ['%title%' => $category->getTitle(), '%direction%' => $directionMessage]
            );
        } catch (Exception $exception) {
            $this->addErrorMessage($exception->getMessage());
        }
        return $this->redirectToRoute('admin_category_index');
    }
}
