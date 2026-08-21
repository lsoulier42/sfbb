<?php

namespace App\Service;

use App\Contract\Service\NotificationServiceInterface;
use App\Entity\Chat;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;

class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private readonly NotificationRepository $notificationRepository
    ) {
    }

    public function notify(
        User $user,
        string $type,
        ?Chat $chat = null,
        ?User $actor = null,
        ?string $message = null
    ): Notification {
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setType($type);
        $notification->setChat($chat);
        $notification->setActor($actor);
        $notification->setMessage($message);
        $notification->setIsRead(false);

        $this->notificationRepository->createOrUpdate($notification);

        return $notification;
    }

    public function findForUser(User $user, int $limit = 10): array
    {
        return $this->notificationRepository->findForUser($user, $limit);
    }

    public function countUnread(User $user): int
    {
        return $this->notificationRepository->countUnread($user);
    }

    public function markAllAsRead(User $user): void
    {
        $this->notificationRepository->markAllAsRead($user);
    }

    public function markChatAsRead(User $user, Chat $chat): void
    {
        $this->notificationRepository->markChatAsRead($user, $chat);
    }
}
