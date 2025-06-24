<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Listeners;

use Longman\TelegramBot\Entities\ChatMemberUpdated;
use MXRVX\Telegram\Bot\Models\User;

class ChatMemberListener extends UserListener
{
    public function execute(): void
    {
        $chatMember = $this->getMyChatMember();

        if ($chatMember && $data = $this->getUserDataFromMyChatMember($chatMember)) {
            if ($user = $this->getUser($data)) {
                $user->save();
            }
        }
    }

    /**
     * Extract user data from ChatMemberUpdated entity
     */
    public function getUserDataFromMyChatMember(ChatMemberUpdated $chatMember): ?array
    {
        return $this->getUserData(
            $chatMember->getFrom(),
            $chatMember->getNewChatMember()->getStatus(),
        );
    }
}
