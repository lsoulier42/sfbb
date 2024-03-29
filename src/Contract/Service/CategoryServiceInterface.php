<?php

namespace App\Contract\Service;

use App\Dto\ViewModel\CategoryViewModel;
use App\Entity\Category;
use App\Enum\ChangeOrderEnum;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;

interface CategoryServiceInterface
{
    /**
     * @return Collection<Category>
     */
    public function listAll(): Collection;

    /**
     * @return Collection<CategoryViewModel>
     */
    public function getCategoryViewModels(): Collection;

    /**
     * @throws NonUniqueResultException
     */
    public function createNewCategory(Category $category): Category;

    public function editCategory(Category $category): Category;

    public function deleteCategory(Category $category): void;

    public function changeOrder(Category $category, ChangeOrderEnum $direction): void;
}
