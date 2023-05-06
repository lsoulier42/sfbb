<?php

namespace App\Repository;

use App\Entity\UserChatView;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserChatView|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserChatView|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserChatView[]    findAll()
 * @method UserChatView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserChatViewRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserChatView::class);
    }
}
