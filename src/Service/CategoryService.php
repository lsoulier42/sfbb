<?php

namespace App\Service;

use App\Contract\Service\CategoryServiceInterface;
use App\Entity\Category;
use App\Enum\ChangeOrderEnum;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use InvalidArgumentException;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    /**
     * @return Collection<Category>
     */
    public function listAll(): Collection
    {
        return $this->categoryRepository->listAll();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function createNewCategory(Category $category): Category
    {
        $lastOrderNumber = $this->categoryRepository->findLastOrderNumber();
        $category->setOrderNumber($lastOrderNumber + 1);
        $this->categoryRepository->createOrUpdate($category);
        return $category;
    }

    public function editCategory(Category $category): Category
    {
        $this->categoryRepository->createOrUpdate($category);
        return $category;
    }

    public function deleteCategory(Category $category): void
    {
        if ($category->getForums()->isEmpty() === false) {
            throw new InvalidArgumentException('category.error.contains_forum');
        }
        $this->categoryRepository->remove($category);
    }

    public function changeOrder(Category $category, ChangeOrderEnum $direction): void
    {
        $next = $this->categoryRepository->findOneAfter($category, $direction);
        if ($next === null) {
            throw new InvalidArgumentException('category.error.change_direction');
        }
        $oldOrder = $next->getOrderNumber();
        $next->setOrderNumber($category->getOrderNumber());
        $category->setOrderNumber($oldOrder);
        $this->categoryRepository->createOrUpdate($category);
    }
}
