<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Forum;
use App\Entity\Topic;
use App\Enum\ChangeOrderEnum;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Forum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forum[]    findAll()
 * @method Forum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }

    public function findForumLastOrderNumber(Category $category): int
    {
        return $this->findLastOrderNumber(self::ALIAS_FORUM, $category);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneForumAfter(Forum $forum, ChangeOrderEnum $direction): ?Forum
    {
        return $this->findOneAfter(self::ALIAS_FORUM, $forum, $direction);
    }
}
