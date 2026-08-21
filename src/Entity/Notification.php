<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: NotificationRepository::class)]
class Notification extends AbstractEntity
{
    #[ManyToOne(targetEntity: User::class, inversedBy: 'notifications')]
    private User $user;

    #[Column(type: Types::STRING, length: 50)]
    private string $type = 'private_message';

    #[ManyToOne(targetEntity: Chat::class)]
    private ?Chat $chat = null;

    #[ManyToOne(targetEntity: User::class)]
    private ?User $actor = null;

    #[Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[Column(type: Types::BOOLEAN)]
    private bool $isRead = false;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Notification
    {
        $this->user = $user;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Notification
    {
        $this->type = $type;
        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): Notification
    {
        $this->chat = $chat;
        return $this;
    }

    public function getActor(): ?User
    {
        return $this->actor;
    }

    public function setActor(?User $actor): Notification
    {
        $this->actor = $actor;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): Notification
    {
        $this->message = $message;
        return $this;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): Notification
    {
        $this->isRead = $isRead;
        return $this;
    }
}
