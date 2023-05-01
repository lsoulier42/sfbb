<?php

namespace App\Service;

use App\Contract\Service\ForumServiceInterface;
use App\Dto\Pager\PagerDto;
use App\Dto\ViewModel\ForumViewModel;
use App\Entity\Category;
use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Topic;
use App\Enum\ChangeOrderEnum;
use App\Repository\ForumRepository;
use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use InvalidArgumentException;
use LogicException;
use Pagerfanta\Pagerfanta;

class ForumService implements ForumServiceInterface
{
    public function __construct(
        private readonly ForumRepository $forumRepository,
        private readonly TopicRepository $topicRepository
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
     * @inheritDoc
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

    /**
     * @inheritDoc
     */
    public function getTopicsByLatestsPostsPaginated(Forum $forum, PagerDto $dto): Pagerfanta
    {
        return $this->topicRepository->findByLatestPostPaginated($forum, $dto);
    }

    /**
     * @inheritDoc
     */
    public function getTopicsByLatestsPosts(Forum $forum): Collection
    {
        return $this->topicRepository->findByLatestPost($forum);
    }

    /**
     * @param Category $category
     * @return Collection<ForumViewModel>
     */
    public function getForumViewModels(Category $category): Collection
    {
        $vms = new ArrayCollection();
        foreach ($category->getForums() as $forum) {
            $vm = $this->getForumViewModel($forum);
            $vms->add($vm);
        }
        return $vms;
    }

    public function getForumViewModel(Forum $forum): ForumViewModel
    {
        $forumViewModel = new ForumViewModel();
        $id = $forum->getId();
        if ($id === null) {
            throw new LogicException("Forum id is not supposed to be null");
        }
        $forumViewModel->setId($id)
            ->setTitle($forum->getTitle())
            ->setSubTitle($forum->getSubTitle())
            ->setNbTopics($forum->getNbTopics())
            ->setNbMessages($forum->getNbMessages());
        $topicsByLastPost = $this->getTopicsByLatestsPosts($forum);
        $lastTopic = $topicsByLastPost->first();
        if (!$lastTopic instanceof Topic) {
            return $forumViewModel;
        }
        $lastPost = $lastTopic->getLastPost();
        $lastMessage = $lastPost instanceof Post ? $lastPost : $lastTopic;
        return $forumViewModel->setLastMessage($lastMessage);
    }
}
