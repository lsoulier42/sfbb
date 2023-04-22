<?php

namespace App\Contract\Service;

use App\Dto\Topic\TopicDto;
use App\Entity\Forum;
use App\Entity\Topic;
use App\Entity\User;

interface TopicServiceInterface
{
    public function createNewTopic(TopicDto $dto, Forum $forum, User $author, bool $flush = true): Topic;

    public function hydrateDtoWithTopic(Topic $topic): TopicDto;

    public function editTopic(Topic $topic, TopicDto $dto, bool $flush = true): Topic;
}
