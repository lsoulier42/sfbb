<?php

namespace App\Contract\Service;

use App\Dto\Topic\PostDto;
use App\Dto\Topic\TopicDto;
use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;

interface MessageServiceInterface
{
    public function createNewTopic(
        TopicDto $dto,
        Forum $forum,
        User $author,
        bool $flush = true
    ): Topic;

    public function hydrateDtoWithTopic(Topic $topic): TopicDto;

    public function editTopic(Topic $topic, TopicDto $dto, bool $flush = true): Topic;

    public function createNewPost(
        PostDto $dto,
        Topic $topic,
        User $author,
        bool $flush = true
    ): Post;

    public function hydrateDtoWithPost(Post $post): PostDto;

    public function editPost(Post $post, PostDto $dto, bool $flush = true): Post;
}
