<?php

namespace App\Dto\Topic;

use Symfony\Component\Validator\Constraints\NotBlank;

class TopicDto
{
    #[NotBlank]
    private string $title;

    #[NotBlank]
    private string $content;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return TopicDto
     */
    public function setTitle(string $title): TopicDto
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return TopicDto
     */
    public function setContent(string $content): TopicDto
    {
        $this->content = $content;
        return $this;
    }
}