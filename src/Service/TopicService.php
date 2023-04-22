<?php

namespace App\Service;

use App\Contract\Service\TopicServiceInterface;
use App\Dto\Topic\TopicDto;
use App\Entity\Forum;
use App\Entity\Topic;
use App\Entity\User;
use App\Helper\HydratorHelper;
use App\Repository\TopicRepository;

class TopicService implements TopicServiceInterface
{
    public function __construct(
        private readonly TopicRepository $topicRepository
    ) {
    }

    public function createNewTopic(
        TopicDto $dto,
        Forum $forum,
        User $author,
        bool $flush = true
    ): Topic {
        $topic = HydratorHelper::convertClassNamingConvention($dto, Topic::class);
        $forum->addTopic($topic);
        $author->addTopic($topic);
        $this->topicRepository->createOrUpdate($topic, $flush);
        return $topic;
    }

    public function hydrateDtoWithTopic(Topic $topic): TopicDto
    {
        return HydratorHelper::convertClassNamingConvention($topic, TopicDto::class);
    }

    public function editTopic(Topic $topic, TopicDto $dto, bool $flush = true): Topic
    {
        $topic = HydratorHelper::updateObjectNamingConvention($dto, $topic);
        $this->topicRepository->createOrUpdate($topic, $flush);
        return $topic;
    }
}
