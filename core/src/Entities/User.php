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

/*
 *
 * 'defaultValue' => 'CURRENT_TIMESTAMP'
 * public const STATUS_CREATOR = 'creator';
    public const STATUS_ADMINISTRATOR = 'administrator';
    public const STATUS_MEMBER = 'member';
    public const STATUS_RESTRICTED = 'restricted';
    public const STATUS_LEFT = 'left';
    public const STATUS_KICKED = 'kicked';
    public const STATUS_UNKNOWN = 'unknown';

 <object class="mxrvxTelegramBotUser" table="mxrvx_telegram_bot_users" extends="xPDOObject">
        <field key="id" dbtype="bigint" precision="20" phptype="integer" null="false" index="pk" attributes="unsigned" />
        <field key="first_name" dbtype="varchar" precision="191" phptype="string" null="true" default="" />
        <field key="last_name" dbtype="varchar" precision="191" phptype="string" null="true" default="" />
        <field key="username" dbtype="varchar" precision="191" phptype="string" null="true" default="" />
        <field key="status" dbtype="varchar" precision="100" phptype="string" null="true" default="" />
        <field key="created_at" dbtype="int" precision="20" phptype="timestamp" null="true" default="0"/>
        <field key="updated_at" dbtype="int" precision="20" phptype="timestamp" null="true" default="0"/>

        <index alias="PRIMARY" name="PRIMARY" primary="true" unique="true" type="BTREE">
            <column key="id" length="" collation="A" null="false"/>
        </index>
        <index alias="first_name" name="first_name" primary="false" unique="false" type="BTREE">
            <column key="first_name" length="" collation="A" null="false"/>
        </index>
        <index alias="last_name" name="last_name" primary="false" unique="false" type="BTREE">
            <column key="last_name" length="" collation="A" null="false"/>
        </index>
        <index alias="username" name="username" primary="false" unique="false" type="BTREE">
            <column key="username" length="" collation="A" null="false"/>
        </index>
        <index alias="status" name="status" primary="false" unique="false" type="BTREE">
            <column key="status" length="" collation="A" null="false"/>
        </index>
        <index alias="created_at" name="created_at" primary="false" unique="false" type="BTREE">
            <column key="created_at" length="" collation="A" null="false"/>
        </index>
        <index alias="updated_at" name="updated_at" primary="false" unique="false" type="BTREE">
            <column key="updated_at" length="" collation="A" null="false"/>
        </index>
    </object>
 */
