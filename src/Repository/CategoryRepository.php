<?php

namespace App\Repository;

use App\Entity\Category;
use App\Enum\ChangeOrderEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function listAll(): Collection
    {
        $alias = self::ALIAS_CATEGORY;
        $queryBuilder = $this->createQueryBuilder($alias);
        $orderField = self::ORDER_FIELD;
        $queryBuilder->orderBy("$alias.$orderField", Criteria::ASC);
        return new ArrayCollection($queryBuilder->getQuery()->getResult());
    }

    public function findCategoryLastOrderNumber(): int
    {
        return $this->findLastOrderNumber(self::ALIAS_CATEGORY);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneCategoryAfter(Category $category, ChangeOrderEnum $direction): ?Category
    {
        return $this->findOneAfter(self::ALIAS_CATEGORY, $category, $direction);
    }
}
