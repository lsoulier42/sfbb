<?php

namespace App\Controller;

use App\Contract\Service\AccessServiceInterface;
use App\Contract\Service\MessageServiceInterface;
use App\Dto\Topic\TopicDto;
use App\Entity\Forum;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\Topic\TopicType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/topic')]
class TopicController extends BaseController
{
    #[Route(path: '/{forum}/new', name: 'topic_new')]
    public function new(
        Request $request,
        Forum $forum,
        #[CurrentUser] User $user,
        MessageServiceInterface $topicService
    ): Response|RedirectResponse {
        $dto = new TopicDto();
        $form = $this->createForm(TopicType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $topic = $topicService->createNewTopic($dto, $forum, $user);
            $this->addSuccessMessage('topic.success.create', ['%topic%' => $topic->getTitle()]);
            return $this->redirectToRoute(
                'topic_show',
                [
                    'topic' => $topic->getId()
                ]
            );
        }
        return $this->render(
            'topic/new.html.twig',
            [
                'form' => $form->createView(),
                'author' => $user,
                'forum' => $forum
            ]
        );
    }

    #[Route(path: '/{topic}/edit', name: 'topic_edit')]
    public function edit(
        Request $request,
        Topic $topic,
        #[CurrentUser] User $user,
        MessageServiceInterface $topicService,
        AccessServiceInterface $accessService
    ): Response|RedirectResponse {
        if (!$accessService->canEditPost($user, $topic)) {
            $this->addErrorMessage('topic.error.cant_edit');
            return $this->redirectToRoute('topic_show', ['topic' => $topic->getId()]);
        }
        $dto = $topicService->hydrateDtoWithTopic($topic);
        $form = $this->createForm(TopicType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $topic = $topicService->editTopic($topic, $dto);
            $this->addSuccessMessage('topic.success.edit', ['%topic%' => $topic->getTitle()]);
            return $this->redirectToRoute(
                'topic_show',
                [
                    'topic' => $topic->getId()
                ]
            );
        }
        return $this->render(
            'topic/edit.html.twig',
            [
                'form' => $form->createView(),
                'topic' => $topic
            ]
        );
    }

    #[Route(path: '/{topic}/show', name: 'topic_show')]
    public function show(
        Request $request,
        Topic $topic,
        PostRepository $postRepository
    ): Response {
        $posts = $postRepository->findAllPaginated(
            $topic,
            self::hydratePagerDto($request)
        );
        return $this->render(
            'topic/show.html.twig',
            [
                'topic' => $topic,
                'posts' => $posts
            ]
        );
    }
}
