<?php

namespace App\Repository;

use App\Entity\UserTopicView;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTopicView|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTopicView|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTopicView[]    findAll()
 * @method UserTopicView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTopicViewRepository extends BaseRepository
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, UserTopicView::class);
    }
}
