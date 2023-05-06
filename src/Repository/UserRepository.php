<?php

namespace App\Repository;

use App\Dto\Pager\PagerDto;
use App\Dto\User\MemberFilterDto;
use App\Entity\User;
use App\Enum\RoleEnum;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends BaseRepository implements PasswordUpgraderInterface
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->createOrUpdate($user);
    }

    /**
     * @return Collection<User>
     */
    public function findByOnline(): Collection
    {
        $aliasUser = self::ALIAS_USER;
        $queryBuilder = $this->createQueryBuilder($aliasUser);
        $fieldName = self::LAST_ACTIVITY_FIELD;
        $queryBuilder->andWhere("$aliasUser.$fieldName IS NOT NULL");
        $queryBuilder->andWhere("$aliasUser.$fieldName > DATE_SUB(CURRENT_TIMESTAMP(), 5, 'minute')");
        return self::getCollectionFromQueryBuilder($queryBuilder);
    }

    /**
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByLastRegistered(): ?User
    {
        $aliasUser = self::ALIAS_USER;
        $queryBuilder = $this->createQueryBuilder($aliasUser);
        $fieldName = self::CREATED_AT_FIELD;
        $queryBuilder->orderBy("$aliasUser.$fieldName", Criteria::DESC);
        $queryBuilder->setMaxResults(1);
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function getNbRegisteredUsers(): int
    {
        $aliasUser = self::ALIAS_USER;
        $queryBuilder = $this->createQueryBuilder($aliasUser);
        $queryBuilder->select("COUNT($aliasUser.id) as nb_registered_users");
        return $queryBuilder->getQuery()->getResult()[0]['nb_registered_users'];
    }

    /**
     * @param PagerDto $dto
     * @return Pagerfanta<User>
     */
    public function findAllPaginated(PagerDto $dto): Pagerfanta
    {
        return self::createPaginator($this->createQueryBuilder(self::ALIAS_USER), $dto);
    }

    /**
     * @param MemberFilterDto $dto
     * @return Pagerfanta<User>
     */
    public function findByFilterDtoPaginated(MemberFilterDto $dto): Pagerfanta
    {
        $alias = self::ALIAS_USER;
        $queryBuilder = $this->createQueryBuilder($alias);
        $username = $dto->getUsername();
        if (!empty($username)) {
            self::addUsernameLike($queryBuilder, $username, $alias);
        }
        $name = $dto->getName();
        if (!empty($name)) {
            self::addNameLike($queryBuilder, $name, $alias);
        }
        $city = $dto->getCity();
        if (!empty($city)) {
            self::addCityLike($queryBuilder, $city, $alias);
        }
        $role = $dto->getRole();
        if ($role !== null) {
            self::addRoleWhere($queryBuilder, $role, $alias);
        }
        return self::createPaginator($queryBuilder, $dto);
    }

    public static function addUsernameLike(
        QueryBuilder $queryBuilder,
        string $username,
        string $alias
    ): QueryBuilder {
        return self::addFieldLike(
            $queryBuilder,
            $alias,
            self::USERNAME_FIELD,
            $username
        );
    }

    public static function addNameLike(
        QueryBuilder $queryBuilder,
        string $name,
        string $aliasUser
    ): QueryBuilder {
        return self::addMultipleFieldsLikeSameValue(
            $queryBuilder,
            $aliasUser,
            [self::FIRST_NAME_FIELD, self::LAST_NAME_FIELD],
            $name
        );
    }

    public static function addCityLike(
        QueryBuilder $queryBuilder,
        string $city,
        string $aliasUser,
    ): QueryBuilder {
        return self::addFieldLike(
            $queryBuilder,
            $aliasUser,
            self::CITY_FIELD,
            $city
        );
    }

    public static function addRoleWhere(
        QueryBuilder $queryBuilder,
        RoleEnum $role,
        string $aliasUser
    ): QueryBuilder {
        $roleName = self::ROLES_FIELD;
        return $queryBuilder->andWhere("JSON_GET_TEXT($aliasUser.$roleName, 0) = :$roleName")
            ->setParameter($roleName, $role->name);
    }
}
