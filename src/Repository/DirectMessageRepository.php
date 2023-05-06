<?php

namespace App\Repository;

use App\Entity\DirectMessage;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DirectMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DirectMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DirectMessage[]    findAll()
 * @method DirectMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectMessageRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DirectMessage::class);
    }
}
