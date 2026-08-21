<?php

namespace App\Contract\Service;

use App\Dto\Pager\PagerDto;
use App\Entity\Chat;
use App\Entity\DirectMessage;
use App\Entity\User;
use Pagerfanta\Pagerfanta;

interface ConversationServiceInterface
{
    public function createPrivateChat(User $userA, User $userB, ?string $title = null): Chat;

    public function deleteChat(Chat $chat): void;

    /**
     * @return Chat[]
     */
    /**
     * @return Chat[]
     */
    public function findInboxForUser(User $user): array;

    public function findInboxForUserPaginated(User $user, PagerDto $pager, ?string $search = null): Pagerfanta;

    public function sendMessage(Chat $chat, User $author, string $content): DirectMessage;

    public function countUnreadInChat(Chat $chat, User $user): int;

    public function getUnreadCountForUser(User $user): int;

    public function markAsRead(Chat $chat, User $user): void;

    public function markAsUnread(Chat $chat, User $user): void;

    public function toggleRead(Chat $chat, User $user): void;

    public function markAllAsRead(User $user): void;
}
