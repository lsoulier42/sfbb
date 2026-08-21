<?php

namespace App\Service;

use App\Contract\Service\ConversationServiceInterface;
use App\Entity\Chat;
use App\Entity\DirectMessage;
use App\Entity\User;
use App\Entity\UserChatView;
use App\Repository\ChatRepository;
use App\Repository\DirectMessageRepository;
use App\Repository\UserChatViewRepository;
use DateTimeImmutable;

class ConversationService implements ConversationServiceInterface
{
    public function __construct(
        private readonly ChatRepository $chatRepository,
        private readonly DirectMessageRepository $directMessageRepository,
        private readonly UserChatViewRepository $userChatViewRepository
    ) {
    }

    public function createPrivateChat(User $userA, User $userB, ?string $title = null): Chat
    {
        $chat = new Chat();
        $chat->setTitle($title !== null && $title !== '' ? $title : self::DEFAULT_TITLE);
        $chat->addParticipant($userA);
        $chat->addParticipant($userB);
        $this->chatRepository->createOrUpdate($chat);

        return $chat;
    }

    public function findInboxForUser(User $user): array
    {
        return $this->chatRepository->findInboxForUser($user);
    }

    public function sendMessage(Chat $chat, User $author, string $content): DirectMessage
    {
        $message = new DirectMessage();
        $message->setContent($content);
        $message->setAuthor($author);
        $message->setChat($chat);
        $author->addDirectMessage($message);
        $chat->addDirectMessage($message);
        $this->directMessageRepository->createOrUpdate($message);

        return $message;
    }

    public function countUnreadInChat(Chat $chat, User $user): int
    {
        return $this->chatRepository->countUnreadInChat($chat, $user);
    }

    public function getUnreadCountForUser(User $user): int
    {
        return $this->chatRepository->countUnreadForUser($user);
    }

    public function markAsRead(Chat $chat, User $user): void
    {
        $view = $this->userChatViewRepository->findOneBy(
            [
                'chat' => $chat,
                'user' => $user,
            ]
        );

        if ($view === null) {
            $view = new UserChatView();
            $view->setChat($chat);
            $view->setUser($user);
        }

        $view->setLastSeen(new DateTimeImmutable());
        $this->userChatViewRepository->createOrUpdate($view);
    }
}
