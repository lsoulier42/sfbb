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

    /**
     * @throws NonUniqueResultException
     */
    public function findLastOrderNumber(): int
    {
        $alias = self::ALIAS_CATEGORY;
        $queryBuilder = $this->createQueryBuilder($alias);
        $orderField = self::ORDER_FIELD;
        $queryBuilder->select("MAX($alias.$orderField) as last_order_number");
        $result = $queryBuilder->getQuery()->getArrayResult();
        $lastNumber = current($result)['last_order_number'];
        return $lastNumber === null ? 0 : $lastNumber;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneAfter(Category $category, ChangeOrderEnum $direction): ?Category
    {
        $alias = self::ALIAS_CATEGORY;
        $queryBuilder = $this->createQueryBuilder($alias);
        $orderField = self::ORDER_FIELD;
        $condition = $direction === ChangeOrderEnum::UP ? '<' : '>';
        $criteria = $direction === ChangeOrderEnum::UP ? Criteria::DESC : Criteria::ASC;
        $queryBuilder->andWhere("$alias.$orderField $condition :$orderField")
            ->setParameter($orderField, $category->getOrderNumber())
            ->orderBy("$alias.$orderField", $criteria)
            ->setMaxResults(1);
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
