<?php

namespace App\Repository;

use App\Dto\Pager\PagerDto;
use App\Entity\Category;
use App\Enum\ChangeOrderEnum;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

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

    /**
     * @return Collection<Category>
     */
    public function listAll(): Collection
    {
        return self::getCollectionFromQueryBuilder($this->queryBuilderListAll());
    }

    private function queryBuilderListAll(): QueryBuilder
    {
        $alias = self::ALIAS_CATEGORY;
        $queryBuilder = $this->createQueryBuilder($alias);
        $orderField = self::ORDER_FIELD;
        return $queryBuilder->orderBy("$alias.$orderField", Criteria::ASC);
    }

    /**
     * @param PagerDto $dto
     * @return Pagerfanta<Category>
     */
    public function listPaginated(PagerDto $dto): Pagerfanta
    {
        return self::createPaginator($this->queryBuilderListAll(), $dto);
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
