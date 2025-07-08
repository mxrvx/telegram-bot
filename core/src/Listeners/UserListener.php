<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Listeners;

use Longman\TelegramBot\Entities\User as TelegramUser;
use MXRVX\Telegram\Bot\Entities\User;
use MXRVX\Telegram\Bot\Entities\UserStatus;
use MXRVX\Telegram\Bot\Listener;

/**
 * @psalm-import-type MetaData from User
 */
abstract class UserListener extends Listener
{
    public function getUser(array $data): ?User
    {
        $user = User::findByPK($data[User::FIELD_ID]) ?? User::make([]);
        $user->fromArray($data);

        return $user->id ? $user : null;
    }

    /**
     * @param TelegramUser $entity Telegram user entity
     * @param string|UserStatus $status User status
     *
     * @psalm-return null|MetaData
     */
    public function getUserData(TelegramUser $entity, string|UserStatus $status = UserStatus::Member): ?array
    {
        if (!$userId = $entity->getId()) {
            return null;
        }
        return [
            User::FIELD_ID => $userId,
            User::FIELD_FIRST_NAME => $entity->getFirstName(),
            User::FIELD_LAST_NAME => $entity->getLastName(),
            User::FIELD_USERNAME => $entity->getUsername(),
            User::FIELD_STATUS => $status instanceof UserStatus ? $status : UserStatus::make($status),
        ];
    }
}
