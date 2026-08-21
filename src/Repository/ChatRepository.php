<?php

namespace App\Repository;

use App\Dto\Pager\PagerDto;
use App\Entity\Chat;
use App\Entity\DirectMessage;
use App\Entity\User;
use App\Entity\UserChatView;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

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

    public function findInboxForUserPaginated(User $user, PagerDto $pager, ?string $search = null): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c', 'MAX(dm.createdAt) AS HIDDEN lastActivity')
            ->leftJoin('c.directMessages', 'dm')
            ->innerJoin('c.participants', 'p')
            ->leftJoin('c.participants', 'other')
            ->where('p = :user')
            ->setParameter('user', $user);

        if ($search !== null && $search !== '') {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->orX(
                        'c.title LIKE :search',
                        'other.username LIKE :search'
                    )
                )
                ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder
            ->groupBy('c.id')
            ->orderBy('lastActivity', 'DESC');

        return self::createPaginator($queryBuilder, $pager);
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
