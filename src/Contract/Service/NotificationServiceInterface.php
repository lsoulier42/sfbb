<?php

namespace App\Contract\Service;

use App\Entity\Chat;
use App\Entity\Notification;
use App\Entity\User;

interface NotificationServiceInterface
{
    public function notify(
        User $user,
        string $type,
        ?Chat $chat = null,
        ?User $actor = null,
        ?string $message = null
    ): Notification;

    /**
     * @return Notification[]
     */
    public function findForUser(User $user, int $limit = 10): array;

    public function countUnread(User $user): int;

    public function markAllAsRead(User $user): void;

    public function markChatAsRead(User $user, Chat $chat): void;
}
