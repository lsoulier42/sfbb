<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Forum;
use App\Enum\ChangeOrderEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    public const ALIAS_CATEGORY = 'category';
    public const ALIAS_FORUM = 'forum';

    public const TITLE_FIELD = 'title';
    public const ORDER_FIELD = 'orderNumber';
    public const CATEGORY_FIELD = 'category';

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function createOrUpdate(mixed $entity, bool $flush = true): void
    {
        if ($entity->getId() === null) {
            $this->getEntityManager()->persist($entity);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(mixed $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastOrderNumber(string $alias, ?Category $category = null): int
    {
        $queryBuilder = $this->createQueryBuilder($alias);
        $orderField = self::ORDER_FIELD;
        $queryBuilder->select("MAX($alias.$orderField) as last_order_number");
        if ($category !== null) {
            self::addCategoryWhere($queryBuilder, $category, $alias);
        }
        $result = $queryBuilder->getQuery()->getArrayResult();
        $lastNumber = current($result)['last_order_number'];
        return $lastNumber === null ? 0 : $lastNumber;
    }

    public static function addFieldLike(
        QueryBuilder $queryBuilder,
        string $alias,
        string $fieldName,
        mixed $fieldValue,
    ): QueryBuilder {
        $fieldWithAlias = "$alias.$fieldName";
        return $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->like($fieldWithAlias, $queryBuilder->expr()->literal("%$fieldValue%")),
            $queryBuilder->expr()->like($fieldWithAlias, $queryBuilder->expr()->literal("$fieldValue%"))
        ));
    }

    public static function addFieldAndWhere(
        QueryBuilder $queryBuilder,
        string $alias,
        string $fieldName,
        mixed $fieldValue,
    ): QueryBuilder {
        return $queryBuilder->andWhere("$alias.$fieldName = :$fieldName")
            ->setParameter($fieldName, $fieldValue);
    }

    public static function addTableJoin(
        QueryBuilder $queryBuilder,
        string $parentAlias,
        string $relationField,
        string $childAlias,
        string $joinType = Join::LEFT_JOIN
    ): QueryBuilder {
        if (self::hasAlias($queryBuilder, $childAlias)) {
            return $queryBuilder;
        }
        $relation = "$parentAlias.$relationField";
        if ($joinType === Join::INNER_JOIN) {
            return $queryBuilder->innerJoin($relation, $childAlias);
        }
        return $queryBuilder->leftJoin($relation, $childAlias);
    }

    private static function hasAlias(
        QueryBuilder $queryBuilder,
        string $alias
    ): bool {
        return in_array($alias, $queryBuilder->getAllAliases(), true);
    }

    public static function addCategoryWhere(
        QueryBuilder $queryBuilder,
        Category $category,
        string $alias
    ): QueryBuilder {
        return self::addFieldAndWhere(
            $queryBuilder,
            $alias,
            self::CATEGORY_FIELD,
            $category
        );
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneAfter(string $alias, Category|Forum $entity, ChangeOrderEnum $direction): mixed
    {
        $queryBuilder = $this->createQueryBuilder($alias);
        $orderField = self::ORDER_FIELD;
        $condition = $direction === ChangeOrderEnum::UP ? '<' : '>';
        $criteria = $direction === ChangeOrderEnum::UP ? Criteria::DESC : Criteria::ASC;
        if ($entity instanceof Forum) {
            self::addCategoryWhere($queryBuilder, $entity->getCategory(), $alias);
        }
        $queryBuilder->andWhere("$alias.$orderField $condition :$orderField")
            ->setParameter($orderField, $entity->getOrderNumber())
            ->orderBy("$alias.$orderField", $criteria)
            ->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
