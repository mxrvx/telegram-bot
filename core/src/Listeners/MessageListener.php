<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Listeners;

use Longman\TelegramBot\Entities\Message;
use MXRVX\Telegram\Bot\Models\User;

/**
 * @psalm-import-type UserArrayStructure from User
 */
class MessageListener extends UserListener
{
    public function execute(): void
    {
        $entity = $this->getMessage();

        if ($entity && $data = $this->getUserDataFromMessage($entity)) {
            if ($user = $this->getUser($data)) {
                $user->fromArray($data, '', true, true);
                $user->save();
            }
        }
    }

    /**
     * Extract user data from Message entity
     *
     * @return UserArrayStructure|null
     */
    public function getUserDataFromMessage(Message $message): ?array
    {
        return $this->extractUserData(
            $message->getFrom(),
        );
    }
}
