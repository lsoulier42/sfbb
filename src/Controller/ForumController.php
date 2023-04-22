<?php

namespace App\Controller;

use App\Entity\Forum;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum')]
class ForumController extends BaseController
{
    #[Route(path: '/{forum}/show', name: 'forum_show')]
    public function show(Forum $forum): Response
    {
        return $this->render(
            'forum/show.html.twig',
            [
                'forum' => $forum
            ]
        );
    }
}
