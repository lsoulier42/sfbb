<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\DirectMessage;
use App\Entity\User;
use App\Entity\UserChatView;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    /**
     * @return Chat[]
     */
    public function findByParticipant(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.participants', 'p')
            ->where('p = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Chat[]
     */
    public function findInboxForUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'MAX(dm.createdAt) AS HIDDEN lastActivity')
            ->leftJoin('c.directMessages', 'dm')
            ->innerJoin('c.participants', 'p')
            ->where('p = :user')
            ->setParameter('user', $user)
            ->groupBy('c.id')
            ->orderBy('lastActivity', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countUnreadInChat(Chat $chat, User $user): int
    {
        return (int)$this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(dm.id)')
            ->from(DirectMessage::class, 'dm')
            ->leftJoin(
                UserChatView::class,
                'ucv',
                'WITH',
                'ucv.chat = :chat AND ucv.user = :user'
            )
            ->where('dm.chat = :chat')
            ->andWhere('dm.author != :user')
            ->andWhere('ucv.lastSeen IS NULL OR dm.createdAt > ucv.lastSeen')
            ->setParameter('chat', $chat)
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUnreadForUser(User $user): int
    {
        return (int)$this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(dm.id)')
            ->from(DirectMessage::class, 'dm')
            ->innerJoin('dm.chat', 'c')
            ->innerJoin('c.participants', 'p')
            ->leftJoin(
                UserChatView::class,
                'ucv',
                'WITH',
                'ucv.chat = c AND ucv.user = :user'
            )
            ->where('p = :user')
            ->andWhere('dm.author != :user')
            ->andWhere('ucv.lastSeen IS NULL OR dm.createdAt > ucv.lastSeen')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
