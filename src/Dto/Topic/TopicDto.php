<?php

namespace App\Dto\Topic;

use Symfony\Component\Validator\Constraints\NotBlank;

class TopicDto extends AbstractMessageDto
{
    #[NotBlank]
    private string $title;

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
}