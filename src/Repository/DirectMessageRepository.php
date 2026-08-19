<?php

namespace App\Repository;

use App\Dto\Pager\PagerDto;
use App\Entity\Chat;
use App\Entity\DirectMessage;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

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

    public function findThread(Chat $chat, PagerDto $pager): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('dm')
            ->where('dm.chat = :chat')
            ->setParameter('chat', $chat)
            ->orderBy('dm.createdAt', 'ASC');

        return self::createPaginator($queryBuilder, $pager);
    }
}
