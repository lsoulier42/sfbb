<?php

namespace App\Controller;

use App\Contract\Service\ConfigurationServiceInterface;
use App\Contract\Service\ConversationServiceInterface;
use App\Contract\Service\NotificationServiceInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends BaseController
{
    public function renderMenu(
        ConfigurationServiceInterface $configurationService,
        ConversationServiceInterface $conversationService,
        NotificationServiceInterface $notificationService
    ): Response {
        $user = $this->getUser();
        $unread = $user instanceof User ? $conversationService->getUnreadCountForUser($user) : 0;
        $notifications = $user instanceof User ? $notificationService->findForUser($user, 10) : [];
        $unreadNotifications = $user instanceof User ? $notificationService->countUnread($user) : 0;

        return $this->render(
            'shared/layout/_menu.html.twig',
            [
                'forum_name' => $configurationService->getConfigValue('forum_name'),
                'forum_description' => $configurationService->getConfigValue('forum_description'),
                'user' => $user,
                'unread_messages' => $unread,
                'notifications' => $notifications,
                'unread_notifications' => $unreadNotifications,
            ]
        );
    }
}
