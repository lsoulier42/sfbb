<?php

namespace App\Dto\Message;

use App\Entity\User;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendMessageDto
{
    #[NotBlank]
    private string $content = '';

    private ?User $recipient = null;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): SendMessageDto
    {
        $this->content = $content;
        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): SendMessageDto
    {
        $this->recipient = $recipient;
        return $this;
    }
}
