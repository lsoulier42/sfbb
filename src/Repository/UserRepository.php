<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
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
        $aliasProfile = self::ALIAS_PROFILE;
        $queryBuilder = $this->createQueryBuilder($aliasUser);
        self::addTableJoin(
            $queryBuilder,
            $aliasUser,
            'profile',
            $aliasProfile
        );
        $fieldName = self::LAST_ACTIVITY_FIELD;
        $queryBuilder->andWhere("$aliasProfile.$fieldName IS NOT NULL");
        $queryBuilder->andWhere("$aliasProfile.$fieldName > DATE_SUB(CURRENT_TIMESTAMP(), 5, 'minute')");
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
}
