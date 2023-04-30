<?php

namespace App\Dto\Homepage;

class BreadcrumbElement
{
    public function __construct(
        private string $title,
        private string $link
    ) {
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
     * @return BreadcrumbElement
     */
    public function setTitle(string $title): BreadcrumbElement
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return BreadcrumbElement
     */
    public function setLink(string $link): BreadcrumbElement
    {
        $this->link = $link;
        return $this;
    }
}
