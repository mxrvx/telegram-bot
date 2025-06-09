<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Listeners;

use Longman\TelegramBot\Entities\ChatMemberUpdated;
use MXRVX\Telegram\Bot\Models\User;

/**
 * @psalm-import-type UserArrayStructure from User
 */
class ChatMemberListener extends UserListener
{
    public function execute(): void
    {
        $entity = $this->getMyChatMember();

        if ($entity && $data = $this->getUserDataFromMyChatMember($entity)) {
            if ($user = $this->getUser($data)) {
                $user->fromArray($data, '', true, true);
                $user->save();
            }
        }
    }

    /**
     * Extract user data from ChatMemberUpdated entity
     *
     * @return UserArrayStructure|null
     * @psalm-return UserArrayStructure|null
     */
    public function getUserDataFromMyChatMember(ChatMemberUpdated $chatMember): ?array
    {
        return $this->extractUserData(
            $chatMember->getFrom(),
            $chatMember->getNewChatMember()->getStatus(),
        );
    }
}
