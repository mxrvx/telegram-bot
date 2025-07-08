<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Entities;

interface UserMetaData
{
    public const FIELD_ID = 'id';
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_USERNAME = 'username';
    public const FIELD_STATUS = 'status';
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';
}
