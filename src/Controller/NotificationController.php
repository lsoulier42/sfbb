<?php

namespace App\Controller;

use App\Contract\Service\NotificationServiceInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/notification')]
class NotificationController extends BaseController
{
    #[Route('/mark-all-read', name: 'notification_mark_all_read', methods: ['POST'])]
    public function markAllRead(
        Request $request,
        #[CurrentUser] User $user,
        NotificationServiceInterface $notificationService
    ): Response {
        if ($this->isCsrfTokenValid('notification-mark-all-read', $request->request->getString('_token'))) {
            $notificationService->markAllAsRead($user);
        } else {
            $this->addErrorMessage('message.error.invalid_token');
        }

        $referer = $request->headers->get('referer');
        if ($referer !== null) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('message_inbox');
    }
}
