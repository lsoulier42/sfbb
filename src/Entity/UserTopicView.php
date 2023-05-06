<?php

namespace App\Entity;

use App\Repository\UserTopicViewRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[Entity(repositoryClass: UserTopicViewRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(
    fields: ['user', 'topic']
)]
class UserTopicView extends AbstractEntity
{
    #[ManyToOne(targetEntity: User::class, inversedBy: 'topicViews')]
    private User $user;

    #[ManyToOne(targetEntity: Topic::class, inversedBy: 'userViews')]
    private Topic $topic;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $lastSeen;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): UserTopicView
    {
        $this->user = $user;
        return $this;
    }

    public function getTopic(): Topic
    {
        return $this->topic;
    }

    public function setTopic(Topic $topic): UserTopicView
    {
        $this->topic = $topic;
        return $this;
    }

    public function getLastSeen(): DateTimeImmutable
    {
        return $this->lastSeen;
    }

    public function setLastSeen(DateTimeImmutable $lastSeen): UserTopicView
    {
        $this->lastSeen = $lastSeen;
        return $this;
    }
}
