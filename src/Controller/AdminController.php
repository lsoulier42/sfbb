<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin'), IsGranted('ROLE_ADMIN')]
class AdminController extends BaseController
{
    #[Route(path: '/', name: 'admin_index', methods: ['GET'])]
    public function index(
        UserRepository $userRepository,
        CategoryRepository $categoryRepository,
        ForumRepository $forumRepository,
        TopicRepository $topicRepository,
        PostRepository $postRepository
    ): Response {
        return $this->render(
            'admin/index.html.twig',
            [
                'nbUsers' => $userRepository->count([]),
                'nbCategories' => $categoryRepository->count([]),
                'nbForums' => $forumRepository->count([]),
                'nbTopics' => $topicRepository->getTotalNbTopics(),
                'nbPosts' => $postRepository->count([]),
                'lastRegisteredUser' => $userRepository->findOneByLastRegistered(),
            ]
        );
    }
}
