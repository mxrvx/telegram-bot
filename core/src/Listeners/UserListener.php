<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Listeners;

use Longman\TelegramBot\Entities\User as TelegramUser;
use MXRVX\Telegram\Bot\Listener;
use MXRVX\Telegram\Bot\Models\User;

abstract class UserListener extends Listener
{
    public function getUser(array $data): ?User
    {
        $user = User::getOrCreateInstance($data)->fill($data);
        return $user->getId() ? $user : null;
    }

    /**
     * @param TelegramUser $entity Telegram user entity
     * @param ?string $status User status
     */
    public function getUserData(TelegramUser $entity, ?string $status = null): ?array
    {
        if (!$userId = $entity->getId()) {
            return null;
        }
        return [
            User::FIELD_ID => $userId,
            User::FIELD_FIRST_NAME => $entity->getFirstName(),
            User::FIELD_LAST_NAME => $entity->getLastName(),
            User::FIELD_USERNAME => $entity->getUsername(),
            User::FIELD_STATUS => $status ?? User::STATUS_MEMBER
        ];
    }

}
