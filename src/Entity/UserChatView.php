<?php

namespace App\Entity;

use App\Repository\UserChatViewRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[Entity(repositoryClass: UserChatViewRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(fields: ['user', 'chat'])]
class UserChatView extends AbstractEntity
{
    #[ManyToOne(targetEntity: User::class, inversedBy: 'chatViews')]
    private User $user;

    #[ManyToOne(targetEntity: Chat::class)]
    private Chat $chat;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $lastSeen;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): UserChatView
    {
        $this->user = $user;
        return $this;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): UserChatView
    {
        $this->chat = $chat;
        return $this;
    }

    public function getLastSeen(): DateTimeImmutable
    {
        return $this->lastSeen;
    }

    public function setLastSeen(DateTimeImmutable $lastSeen): UserChatView
    {
        $this->lastSeen = $lastSeen;
        return $this;
    }
}
