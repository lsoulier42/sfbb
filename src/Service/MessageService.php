<?php

namespace App\Service;

use App\Contract\Service\MessageServiceInterface;
use App\Dto\Topic\PostDto;
use App\Dto\Topic\TopicDto;
use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
use App\Helper\HydratorHelper;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;

class MessageService implements MessageServiceInterface
{
    public function __construct(
        private readonly TopicRepository $topicRepository,
        private readonly PostRepository $postRepository
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

    public function createNewPost(
        PostDto $dto,
        Topic $topic,
        User $author,
        bool $flush = true
    ): Post {
        $post = HydratorHelper::convertClassNamingConvention($dto, Post::class);
        $topic->addPost($post);
        $author->addPost($post);
        $this->postRepository->createOrUpdate($post, $flush);
        return $post;
    }

    public function hydrateDtoWithPost(Post $post): PostDto
    {
        return HydratorHelper::convertClassNamingConvention($post, PostDto::class);
    }

    public function editPost(Post $post, PostDto $dto, bool $flush = true): Post
    {
        $post = HydratorHelper::updateObjectNamingConvention($dto, $post);
        $this->postRepository->createOrUpdate($post, $flush);
        return $post;
    }
}
