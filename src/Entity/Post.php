<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: PostRepository::class)]
#[HasLifecycleCallbacks]
class Post extends AbstractMessage
{
    #[ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    private User $author;

    #[ManyToOne(targetEntity: Topic::class, inversedBy: 'topics')]
    private Topic $topic;

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): Post
    {
        $this->author = $author;
        return $this;
    }

    public function getTopic(): Topic
    {
        return $this->topic;
    }

    public function setTopic(Topic $topic): Post
    {
        $this->topic = $topic;
        return $this;
    }
}
