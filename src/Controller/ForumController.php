<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Service\ForumService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum')]
class ForumController extends BaseController
{
    #[Route(path: '/{forum}/show', name: 'forum_show')]
    public function show(
        Forum $forum,
        ForumService $forumService
    ): Response {
        $topics = $forumService->getTopicsByLatestsPosts($forum);
        return $this->render(
            'forum/show.html.twig',
            [
                'forum' => $forum,
                'topics' => $topics
            ]
        );
    }
}
