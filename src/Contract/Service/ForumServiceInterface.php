<?php

namespace App\Contract\Service;

use App\Dto\Pager\PagerDto;
use App\Dto\ViewModel\ForumViewModel;
use App\Entity\Category;
use App\Entity\Forum;
use App\Entity\Topic;
use App\Enum\ChangeOrderEnum;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Pagerfanta\Pagerfanta;

interface ForumServiceInterface
{
    public function createNewForum(Forum $forum): Forum;

    public function editForum(Forum $forum): Forum;

    public function deleteForum(Forum $forum): void;

    /**
     * @throws NonUniqueResultException
     */
    public function changeOrder(Forum $forum, ChangeOrderEnum $direction): void;

    /**
     * @param Forum $forum
     * @param PagerDto $dto
     * @return Pagerfanta<Topic>
     */
    public function getTopicsByLatestsPostsPaginated(Forum $forum, PagerDto $dto): Pagerfanta;

    /**
     * @param Forum $forum
     * @return Collection<Topic>
     */
    public function getTopicsByLatestsPosts(Forum $forum): Collection;

    /**
     * @param Category $category
     * @return Collection<ForumViewModel>
     */
    public function getForumViewModels(Category $category): Collection;
}
