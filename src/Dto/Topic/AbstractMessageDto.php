<?php

namespace App\Dto\Topic;

use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractMessageDto
{
    #[NotBlank]
    protected string $content;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }


}