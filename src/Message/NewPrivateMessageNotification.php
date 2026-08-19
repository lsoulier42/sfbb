<?php

namespace App\Message;

final class NewPrivateMessageNotification
{
    public function __construct(
        public readonly int $chatId,
        public readonly string $senderUsername,
        public readonly string $recipientEmail,
        public readonly string $preview
    ) {
    }
}
