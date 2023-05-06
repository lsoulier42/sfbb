<?php

namespace App\Repository;

use App\Dto\Pager\PagerDto;
use App\Entity\Category;
use App\Entity\Forum;
use App\Enum\ChangeOrderEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

abstract class BaseRepository extends ServiceEntityRepository
{
    public const ALIAS_CATEGORY = 'category';
    public const ALIAS_FORUM = 'forum';
    public const ALIAS_TOPIC = 'topic';
    public const ALIAS_POST = 'post';
    public const ALIAS_USER = 'user';

    public const TITLE_FIELD = 'title';
    public const ORDER_FIELD = 'orderNumber';
    public const CATEGORY_FIELD = 'category';
    public const FORUM_FIELD = 'forum';
    public const CREATED_AT_FIELD = 'createdAt';
    public const LAST_ACTIVITY_FIELD = 'lastActivity';
    public const POST_FIELD = 'post';
    public const TOPIC_FIELD = 'topic';
    public const USERNAME_FIELD = 'username';
    public const CITY_FIELD = 'city';
    public const ROLES_FIELD = 'roles';
    public const FIRST_NAME_FIELD = 'firstName';
    public const LAST_NAME_FIELD = 'lastName';

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
        $orx = new Orx();
        self::formatOrxLike($queryBuilder, $orx, $alias, $fieldName, $fieldValue);
        return $queryBuilder->andWhere($queryBuilder->expr()->orX($orx));
    }

    private static function formatOrxLike(
        QueryBuilder $queryBuilder,
        Orx $orx,
        string $alias,
        string $fieldName,
        mixed $fieldValue
    ): void {
        $fieldWithAlias = "$alias.$fieldName";
        $likeVersions = ["%$fieldValue%", "$fieldValue%", "%$fieldValue"];
        foreach ($likeVersions as $version) {
            $orx->add($queryBuilder->expr()->like($fieldWithAlias, $queryBuilder->expr()->literal($version)));
        }
    }

    public static function addMultipleFieldsLikeSameValue(
        QueryBuilder $queryBuilder,
        string $alias,
        array $fieldsNames,
        mixed $fieldValue
    ): QueryBuilder {
        $orx = new Orx();
        foreach ($fieldsNames as $fieldName) {
           self::formatOrxLike($queryBuilder, $orx, $alias, $fieldName, $fieldValue);
        }
        return $queryBuilder->andWhere($queryBuilder->expr()->orX($orx));
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

    public static function getCollectionFromQueryBuilder(QueryBuilder $queryBuilder): Collection
    {
        return new ArrayCollection($queryBuilder->getQuery()->getResult());
    }

    public static function createPaginator(
        QueryBuilder $queryBuilder,
        PagerDto $dto
    ): Pagerfanta {
        $adapter = new QueryAdapter($queryBuilder);
        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $dto->getCurrentPage(),
            $dto->getItemsPerPage()
        );
    }
}
