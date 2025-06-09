<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Listeners;

use Longman\TelegramBot\Entities\User as TelegramUser;
use MXRVX\Telegram\Bot\Listener;
use MXRVX\Telegram\Bot\Models\User;

/**
 * @psalm-import-type UserArrayStructure from User
 */
abstract class UserListener extends Listener
{
    /**
     *
     * @param UserArrayStructure $data
     */
    public function getUser(array $data): ?User
    {
        /** @var User|null $user */
        $user = $this->app->modx->getObject(User::class, ['id' => $data['id']]);

        return $user ?? $this->app->modx->newObject(User::class);
    }

    /**
     * @param TelegramUser $entity Telegram user entity
     * @param ?string $status User status
     *
     * @return UserArrayStructure|null
     */
    public function extractUserData(TelegramUser $entity, ?string $status = null): ?array
    {
        if (!$userId = $entity->getId()) {
            return null;
        }

        /** @var UserArrayStructure $data */
        $data =  [
            'id' => $userId,
            'first_name' => $this->safeSubstr($entity->getFirstName()),
            'last_name' => $this->safeSubstr($entity->getLastName()),
            'username' => $this->safeSubstr($entity->getUsername()),
            'status' => $this->safeSubstr($status ?? User::STATUS_MEMBER),
        ];

        return User::validateUserArrayStructure($data) ? $data : null;
    }

    private function safeSubstr(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return \mb_substr($value, 0, 191, 'UTF-8');
    }
}
