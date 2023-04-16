<?php

namespace App\Controller\Admin;

use App\Contract\Service\CategoryServiceInterface;
use App\Contract\Service\ForumServiceInterface;
use App\Controller\BaseController;
use App\Entity\Category;
use App\Entity\Forum;
use App\Enum\ChangeOrderEnum;
use App\Form\Forum\ForumType;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/forum'), IsGranted('ROLE_ADMIN')]
class ForumAdminController extends BaseController
{
    #[Route(path: '/', name: 'forum_index', methods: ['GET'])]
    public function index(CategoryServiceInterface $categoryService): Response
    {
        $categories = $categoryService->listAll();
        return $this->render(
            'admin/forum/index.html.twig',
            [
                'categories' => $categories
            ]
        );
    }

    #[Route(path: '/new/{category}', name: 'forum_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        ForumServiceInterface $forumService,
        ?Category $category = null
    ): Response|RedirectResponse {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum, ['category' => $category]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $forumService->createNewForum($forum);
                $this->addSuccessMessage('forum.success.new', ['%title%' => $forum->getTitle()]);
                return $this->redirectToRoute('forum_edit', ['forum' => $forum->getId()]);
            } catch (Exception $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
            return $this->redirectToRoute('forum_index');
        }
        return $this->render(
            'admin/forum/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route(path: '/{forum}/edit', name: 'forum_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Forum $forum,
        ForumServiceInterface $forumService
    ): Response|RedirectResponse {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $forumService->editForum($forum);
                $this->addSuccessMessage('forum.success.edit', ['%title%' => $forum->getTitle()]);
                return $this->redirectToRoute('forum_edit', ['forum' => $forum->getId()]);
            } catch (Exception $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
            return $this->redirectToRoute('forum_index');
        }
        return $this->render(
            'admin/forum/edit.html.twig',
            [
                'form' => $form->createView(),
                'forum' => $forum
            ]
        );
    }

    #[Route(path: '/{forum}/delete', name: 'forum_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Forum $forum,
        ForumServiceInterface $forumService
    ): JsonResponse {
        if (!$this->isCsrfTokenValid('delete-forum', (string)$request->request->get('token'))) {
            return new JsonResponse('global.error.csrf.invalid', 500);
        }
        try {
            $forumService->deleteForum($forum);
            $this->addSuccessMessage(
                'forum.success.delete',
                ['%title%' => $forum->getTitle()]
            );
            return new JsonResponse("OK", 200);
        } catch (Exception $exception) {
            $this->addErrorMessage($exception->getMessage());
            return new JsonResponse($exception->getMessage(), 500);
        }
    }

    #[Route(
        path: '/{forum}/change-order/{direction}',
        name: 'forum_change_order',
        requirements: ['direction' => '^(up|down)$'],
        methods: ['GET']
    )]
    public function changeOrder(
        Forum $forum,
        string $direction,
        ForumServiceInterface $forumService
    ): RedirectResponse {
        try {
            $changeOrderDirection = ChangeOrderEnum::from($direction);
            $forumService->changeOrder($forum, $changeOrderDirection);
            $directionTranslationKey = $changeOrderDirection === ChangeOrderEnum::UP
                ? 'forum.success.change_order.up'
                : 'forum.success.change_order.down';
            $directionMessage = $this->translator->trans($directionTranslationKey);
            $this->addSuccessMessage(
                'forum.success.change_order.message',
                ['%title%' => $forum->getTitle(), '%direction%' => $directionMessage]
            );
        } catch (Exception $exception) {
            $this->addErrorMessage($exception->getMessage());
        }
        return $this->redirectToRoute('forum_index');
    }
}
