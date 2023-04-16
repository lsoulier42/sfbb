<?php

namespace App\Service;

use App\Contract\Service\ForumServiceInterface;
use App\Entity\Forum;
use App\Enum\ChangeOrderEnum;
use App\Repository\ForumRepository;
use Doctrine\ORM\NonUniqueResultException;
use InvalidArgumentException;

class ForumService implements ForumServiceInterface
{
    public function __construct(
        private readonly ForumRepository $forumRepository
    ) {
    }

    public function createNewForum(Forum $forum): Forum
    {
        $lastOrderNumber = $this->forumRepository->findForumLastOrderNumber($forum->getCategory());
        $forum->setOrderNumber($lastOrderNumber + 1);
        $this->forumRepository->createOrUpdate($forum);
        return $forum;
    }

    public function editForum(Forum $forum): Forum
    {
        $this->forumRepository->createOrUpdate($forum);
        return $forum;
    }

    public function deleteForum(Forum $forum): void
    {
        if ($forum->getTopics()->isEmpty() === false) {
            throw new InvalidArgumentException('forum.error.contains_topics');
        }
        $this->forumRepository->remove($forum);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function changeOrder(Forum $forum, ChangeOrderEnum $direction): void
    {
        $next = $this->forumRepository->findOneForumAfter($forum, $direction);
        if ($next === null) {
            throw new InvalidArgumentException('forum.error.change_direction');
        }
        $oldOrder = $next->getOrderNumber();
        $next->setOrderNumber($forum->getOrderNumber());
        $forum->setOrderNumber($oldOrder);
        $this->forumRepository->createOrUpdate($forum);
    }
}
