<?php

namespace App\Controller;

use App\Contract\Service\ConversationServiceInterface;
use App\Dto\Message\SendMessageDto;
use App\Entity\Chat;
use App\Entity\User;
use App\Form\Message\PrivateMessageType;
use App\Message\NewPrivateMessageNotification;
use App\Repository\DirectMessageRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/message')]
class MessageController extends BaseController
{
    #[Route('', name: 'message_inbox')]
    public function inbox(
        #[CurrentUser] User $user,
        ConversationServiceInterface $conversationService
    ): Response {
        $conversations = [];
        foreach ($conversationService->findInboxForUser($user) as $chat) {
            $conversations[] = [
                'chat' => $chat,
                'other' => $this->getOtherParticipant($chat, $user),
                'unread' => $conversationService->countUnreadInChat($chat, $user),
                'lastMessage' => $chat->getDirectMessages()->last(),
            ];
        }

        return $this->render(
            'message/inbox.html.twig',
            [
                'conversations' => $conversations,
            ]
        );
    }

    #[Route('/compose/{recipient}', name: 'message_compose', defaults: ['recipient' => null])]
    public function compose(
        Request $request,
        ?User $recipient,
        #[CurrentUser] User $user,
        ConversationServiceInterface $conversationService,
        MessageBusInterface $bus
    ): Response|RedirectResponse {
        $dto = new SendMessageDto();
        if ($recipient !== null) {
            $dto->setRecipient($recipient);
        }

        $form = $this->createForm(
            PrivateMessageType::class,
            $dto,
            [
                'with_recipient' => $recipient === null,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $target = $dto->getRecipient();
            if ($target === null) {
                $this->addErrorMessage('message.error.no_recipient');
                return $this->redirectToRoute('message_compose');
            }
            if ($target->getId() === $user->getId()) {
                $this->addErrorMessage('message.error.self');
                return $this->redirectToRoute('message_compose');
            }

            $chat = $conversationService->createPrivateChat($user, $target, $dto->getTitle());
            $conversationService->sendMessage($chat, $user, $dto->getContent());
            $bus->dispatch(
                new NewPrivateMessageNotification(
                    (int)$chat->getId(),
                    (string)$user->getUsername(),
                    $target->getEmail(),
                    $this->preview($dto->getContent())
                )
            );
            $this->addSuccessMessage('message.success.sent');

            return $this->redirectToRoute('message_thread', ['chat' => $chat->getId()]);
        }

        return $this->render(
            'message/compose.html.twig',
            [
                'form' => $form->createView(),
                'recipient' => $recipient,
            ]
        );
    }

    #[Route('/{chat}/thread', name: 'message_thread')]
    public function thread(
        Request $request,
        Chat $chat,
        #[CurrentUser] User $user,
        ConversationServiceInterface $conversationService,
        DirectMessageRepository $directMessageRepository,
        MessageBusInterface $bus
    ): Response|RedirectResponse {
        if (!$chat->getParticipants()->contains($user)) {
            $this->addErrorMessage('message.error.not_participant');
            return $this->redirectToRoute('message_inbox');
        }

        $conversationService->markAsRead($chat, $user);

        $dto = new SendMessageDto();
        $form = $this->createForm(PrivateMessageType::class, $dto, ['with_recipient' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $conversationService->sendMessage($chat, $user, $dto->getContent());
            $other = $this->getOtherParticipant($chat, $user);
            if ($other !== null) {
                $bus->dispatch(
                    new NewPrivateMessageNotification(
                        (int)$chat->getId(),
                        (string)$user->getUsername(),
                        $other->getEmail(),
                        $this->preview($dto->getContent())
                    )
                );
            }
            $this->addSuccessMessage('message.success.sent');

            return $this->redirectToRoute('message_thread', ['chat' => $chat->getId()]);
        }

        return $this->render(
            'message/thread.html.twig',
            [
                'chat' => $chat,
                'other' => $this->getOtherParticipant($chat, $user),
                'messages' => $directMessageRepository->findThread($chat, self::hydratePagerDto($request)),
                'form' => $form->createView(),
            ]
        );
    }

    private function getOtherParticipant(Chat $chat, User $user): ?User
    {
        foreach ($chat->getParticipants() as $participant) {
            if ($participant->getId() !== $user->getId()) {
                return $participant;
            }
        }

        return null;
    }

    private function preview(string $content): string
    {
        $text = trim(strip_tags($content));

        return mb_strlen($text) > 120 ? mb_substr($text, 0, 120) . '…' : $text;
    }
}
