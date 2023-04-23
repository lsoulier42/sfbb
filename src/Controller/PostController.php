<?php

namespace App\Controller;

use App\Contract\Service\MessageServiceInterface;
use App\Dto\Topic\PostDto;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\Topic\PostType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/post')]
class PostController extends BaseController
{
    #[Route(path: '/{topic}/new', name: 'post_new')]
    public function new(
        Request $request,
        Topic $topic,
        #[CurrentUser] User $user,
        MessageServiceInterface $messageService
    ): Response|RedirectResponse {
        $dto = new PostDto();
        $form = $this->createForm(PostType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $messageService->createNewPost($dto, $topic, $user);
            $this->addSuccessMessage('post.success.create', ['%topic%' => $topic->getTitle()]);
            return $this->redirectToRoute('topic_show', ['topic' => $topic->getId()]);
        }
        return $this->render(
            'post/new.html.twig',
            [
                'form' => $form->createView(),
                'topic' => $topic,
                'author' => $user
            ]
        );
    }

    #[Route(path: '/{post}/edit', name: 'post_edit')]
    public function edit(
        Request $request,
        Post $post,
        #[CurrentUser] User $user,
        MessageServiceInterface $messageService
    ): Response|RedirectResponse {
        if ($user !== $post->getAuthor()) {
            throw new UnauthorizedHttpException('post.error.cant_edit');
        }
        $dto = $messageService->hydrateDtoWithPost($post);
        $form = $this->createForm(PostType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $messageService->editPost($post, $dto);
            $topic = $post->getTopic();
            $this->addSuccessMessage('post.success.edit', ['%topic%' => $topic->getTitle()]);
            return $this->redirectToRoute('topic_show', ['topic' => $topic->getId()]);
        }
        return $this->render(
            'post/edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post
            ]
        );
    }
}
