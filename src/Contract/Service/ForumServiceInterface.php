<?php

namespace App\Contract\Service;

use App\Entity\Forum;
use App\Enum\ChangeOrderEnum;
use Doctrine\ORM\NonUniqueResultException;

interface ForumServiceInterface
{
    public function createNewForum(Forum $forum): Forum;

    public function editForum(Forum $forum): Forum;

    public function deleteForum(Forum $forum): void;

    /**
     * @throws NonUniqueResultException
     */
    public function changeOrder(Forum $forum, ChangeOrderEnum $direction): void;
}
