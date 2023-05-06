<?php

namespace App\Entity;

use App\Repository\DirectMessageRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: DirectMessageRepository::class)]
#[HasLifecycleCallbacks]
class DirectMessage extends AbstractMessage
{
    #[ManyToOne(targetEntity: User::class, inversedBy: 'directMessages')]
    private User $author;

    #[ManyToOne(targetEntity: Chat::class, inversedBy: 'directMessages')]
    private Chat $chat;

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): DirectMessage
    {
        $this->author = $author;
        return $this;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): DirectMessage
    {
        $this->chat = $chat;
        return $this;
    }
}
