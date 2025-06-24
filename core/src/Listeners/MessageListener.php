<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Listeners;

use Longman\TelegramBot\Entities\Message;
use MXRVX\Telegram\Bot\Models\User;

class MessageListener extends UserListener
{
    public function execute(): void
    {
        $message = $this->getMessage();

        if ($message && $data = $this->getUserDataFromMessage($message)) {
            if ($user = $this->getUser($data)) {
                $user->save();
            }
        }
    }

    /**
     * Extract user data from Message entity
     */
    public function getUserDataFromMessage(Message $message): ?array
    {
        return $this->getUserData(
            $message->getFrom(),
        );
    }
}
