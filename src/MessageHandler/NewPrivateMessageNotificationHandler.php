<?php

namespace App\MessageHandler;

use App\Message\NewPrivateMessageNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler]
class NewPrivateMessageNotificationHandler
{
    private const FROM_ADDRESS = 'no-reply@sfbb.local';
    private const FROM_NAME = 'Sfbb';

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function __invoke(NewPrivateMessageNotification $notification): void
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM_ADDRESS, self::FROM_NAME)
            ->to($notification->recipientEmail)
            ->subject(
                $this->translator->trans(
                    'message.email.subject',
                    ['%sender%' => $notification->senderUsername],
                    null,
                    'fr'
                )
            )
            ->htmlTemplate('email/private_message_notification.html.twig')
            ->context(
                [
                    'sender' => $notification->senderUsername,
                    'preview' => $notification->preview,
                    'link' => $this->urlGenerator->generate(
                        'message_thread',
                        ['chat' => $notification->chatId],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                ]
            );

        $this->mailer->send($email);
    }
}
