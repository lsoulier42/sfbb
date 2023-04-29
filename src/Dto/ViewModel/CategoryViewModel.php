<?php

namespace App\Dto\ViewModel;

use Doctrine\Common\Collections\Collection;

class CategoryViewModel
{
    private string $title;

    /**
     * @var Collection<ForumViewModel>
     */
    private Collection $forumViewModels;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CategoryViewModel
     */
    public function setTitle(string $title): CategoryViewModel
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getForumViewModels(): Collection
    {
        return $this->forumViewModels;
    }

    /**
     * @param Collection $forumViewModels
     * @return CategoryViewModel
     */
    public function setForumViewModels(Collection $forumViewModels): CategoryViewModel
    {
        $this->forumViewModels = $forumViewModels;
        return $this;
    }
}
