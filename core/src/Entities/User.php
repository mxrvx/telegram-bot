<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Entity\Behavior;
use MXRVX\ORM\AR\AR;

/**
 * @psalm-type MetaData array{
 *   id: int,
 *   first_name: string,
 *   last_name: string,
 *   username: string,
 *   status: UserStatus
 * }
 *
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[Entity(
    role: 'mxrvx-telegram-bot:User',
    table: 'mxrvx_telegram_bot_users',
)]
#[Behavior\CreatedAt(field: 'created_at', column: 'created_at')]
#[Behavior\UpdatedAt(field: 'updated_at', column: 'updated_at')]
#[Index(columns: ['first_name'], name: 'first_name')]
#[Index(columns: ['last_name'], name: 'last_name')]
#[Index(columns: ['username'], name: 'username')]
#[Index(columns: ['status'], name: 'status')]
#[Index(columns: ['created_at'], name: 'created_at')]
#[Index(columns: ['updated_at'], name: 'updated_at')]
class User extends AR implements UserMetaData
{
    #[Column(type: 'bigPrimary', primary: true, typecast: 'int', unsigned: true)]
    public int $id;

    #[Column(type: 'string(191)', default: '', typecast: 'string')]
    public string $first_name = '';

    #[Column(type: 'string(191)', default: '', typecast: 'string')]
    public string $last_name = '';

    #[Column(type: 'string(191)', typecast: 'string')]
    public string $username = '';

    #[Column(type: 'string', default: UserStatus::Unknown->value, typecast: UserStatus::class)]
    public UserStatus $status;

    #[Column(type: 'datetime')]
    public \DateTimeImmutable $created_at;

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $updated_at = null;
}
