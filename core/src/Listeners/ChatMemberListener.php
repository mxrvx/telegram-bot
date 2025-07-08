<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Listeners;

use Longman\TelegramBot\Entities\ChatMemberUpdated;

class ChatMemberListener extends UserListener
{
    public function execute(): void
    {
        $chatMember = $this->getMyChatMember();

        $data = $this->getUserDataFromMyChatMember($chatMember);
        if ($data && $user = $this->getUser($data)) {
            $user->save();
        }
    }

    /**
     * Extract user data from ChatMemberUpdated entity
     */
    public function getUserDataFromMyChatMember(ChatMemberUpdated $chatMember): ?array
    {
        return $this->getUserData($chatMember->getFrom(), $chatMember->getNewChatMember()->getStatus());
    }
}
