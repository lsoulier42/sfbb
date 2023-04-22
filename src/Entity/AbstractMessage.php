<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[HasLifecycleCallbacks]
abstract class AbstractMessage extends AbstractEntity
{
    private User $author;

    #[Column(type: Types::TEXT)]
    protected string $content;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }
}
