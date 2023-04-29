<?php

namespace App\Dto\ViewModel;

use App\Entity\AbstractMessage;

class ForumViewModel
{
    private int $id;

    private string $title;

    private ?string $subTitle = null;

    private int $nbTopics;

    private int $nbMessages;

    private ?AbstractMessage $lastMessage = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ForumViewModel
     */
    public function setId(int $id): ForumViewModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ForumViewModel
     */
    public function setTitle(string $title): ForumViewModel
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    /**
     * @param string|null $subTitle
     * @return ForumViewModel
     */
    public function setSubTitle(?string $subTitle): ForumViewModel
    {
        $this->subTitle = $subTitle;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbTopics(): int
    {
        return $this->nbTopics;
    }

    /**
     * @param int $nbTopics
     * @return ForumViewModel
     */
    public function setNbTopics(int $nbTopics): ForumViewModel
    {
        $this->nbTopics = $nbTopics;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbMessages(): int
    {
        return $this->nbMessages;
    }

    /**
     * @param int $nbMessages
     * @return ForumViewModel
     */
    public function setNbMessages(int $nbMessages): ForumViewModel
    {
        $this->nbMessages = $nbMessages;
        return $this;
    }

    /**
     * @return AbstractMessage|null
     */
    public function getLastMessage(): ?AbstractMessage
    {
        return $this->lastMessage;
    }

    /**
     * @param AbstractMessage|null $lastMessage
     * @return ForumViewModel
     */
    public function setLastMessage(?AbstractMessage $lastMessage): ForumViewModel
    {
        $this->lastMessage = $lastMessage;
        return $this;
    }
}
