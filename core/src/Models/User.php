<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Models;

/**
 * @psalm-type UserArrayStructure = array{
 * id: int,
 * first_name: string,
 * last_name: string,
 * username: string,
 * status: string
 * }
 */
class User extends \xPDOObject
{
    public const STATUS_CREATOR = 'creator';
    public const STATUS_ADMINISTRATOR = 'administrator';
    public const STATUS_MEMBER = 'member';
    public const STATUS_RESTRICTED = 'restricted';
    public const STATUS_LEFT = 'left';
    public const STATUS_KICKED = 'kicked';
    public const STATUS_UNKNOWN = 'unknown';

    /**
     * @psalm-assert-if-true UserArrayStructure $array
     */
    public static function validateUserArrayStructure(array $array): bool
    {
        return isset($array['id']) && \is_int($array['id']) &&
            isset($array['first_name']) && \is_string($array['first_name']) &&
            isset($array['last_name']) && \is_string($array['last_name']) &&
            isset($array['username']) && \is_string($array['username']) &&
            isset($array['status']) && \is_string($array['status']);
    }

    public function save($cacheFlag = null)
    {
        if (parent::isNew()) {
            parent::set('created_at', \time());
        } else {
            parent::set('updated_at', \time());
        }

        return parent::save($cacheFlag);
    }
}
