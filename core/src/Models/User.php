<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Models;

use MXRVX\Telegram\Bot\Tools\Caster;

/**
 * @psalm-type MetaData = array{
 * id: int,
 * first_name: string,
 * last_name: string,
 * username: string,
 * status: string
 * }
 */
class User extends Model
{
    public const FIELD_ID = 'id';
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_USERNAME = 'username';
    public const FIELD_STATUS = 'status';
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';

    public const FIELDS_FOR_QUERY = [
        self::FIELD_ID,
    ];

    public const STATUS_CREATOR = 'creator';
    public const STATUS_ADMINISTRATOR = 'administrator';
    public const STATUS_MEMBER = 'member';
    public const STATUS_RESTRICTED = 'restricted';
    public const STATUS_LEFT = 'left';
    public const STATUS_KICKED = 'kicked';
    public const STATUS_UNKNOWN = 'unknown';

    /**
     * @var array<string, callable>
     */
    protected static array $fieldMappers = [
        self::FIELD_ID => [self::class, 'getMetaDataId'],
        self::FIELD_FIRST_NAME => [self::class, 'getMetaDataFirstName'],
        self::FIELD_LAST_NAME => [self::class, 'getMetaDataLastName'],
        self::FIELD_USERNAME => [self::class, 'getMetaDataUserName'],
        self::FIELD_STATUS => [self::class, 'getMetaDataStatus'],
        self::FIELD_CREATED_AT => [self::class, 'getMetaDataCreatedAt'],
        self::FIELD_UPDATED_AT => [self::class, 'getMetaDataUpdatedAt'],
    ];

    /**
     * @param array<string, mixed> $data
     */
    public static function getMetaDataQueryValues(array $data, array $fields = []): ?array
    {
        /** @psalm-var MetaData|null $metadata */
        if (!$metadata = parent::getMetaDataQueryValues($data, $fields)) {
            return NULL;
        }

        $metadata = \array_filter($metadata, static function ($value) {
            return !empty($value);
        });

        return $metadata ?? NULL;
    }

    public static function getMetaDataId(mixed $value): int
    {
        return Caster::int($value);
    }

    public static function getMetaDataFirstName(mixed $value): string
    {
        return Caster::string($value);
    }

    public static function getMetaDataLastName(mixed $value): string
    {
        return Caster::string($value);
    }

    public static function getMetaDataUserName(mixed $value): string
    {
        return Caster::string($value);
    }

    public static function getMetaDataStatus(mixed $value): string
    {
        return Caster::string($value);
    }

    public static function getMetaDataCreatedAt(mixed $value): int
    {
        return Caster::int($value);
    }

    public static function getMetaDataUpdatedAt(mixed $value): int
    {
        return Caster::int($value);
    }

    public function getId(): string
    {
        return $this->getFieldValue(self::FIELD_ID);
    }

    public function getFirstName(): string
    {
        return $this->getFieldValue(self::FIELD_FIRST_NAME);
    }

    public function getLastName(): string
    {
        return $this->getFieldValue(self::FIELD_LAST_NAME);
    }

    public function getUserName(): string
    {
        return $this->getFieldValue(self::FIELD_USERNAME);
    }

    public function getStatus(): string
    {
        return $this->getFieldValue(self::FIELD_STATUS);
    }

    public function getCreatedAt(): string
    {
        return $this->getFieldValue(self::FIELD_CREATED_AT);
    }

    public function getUpdatedAt(): string
    {
        return $this->getFieldValue(self::FIELD_UPDATED_AT);
    }

    public function setId(mixed $value): self
    {
        return $this->setFieldValue(self::FIELD_ID, $value);
    }

    public function setFirstName(mixed $value): self
    {
        return $this->setFieldValue(self::FIELD_FIRST_NAME, $value);
    }

    public function setLastName(mixed $value): self
    {
        return $this->setFieldValue(self::FIELD_LAST_NAME, $value);
    }

    public function setUserName(mixed $value): self
    {
        return $this->setFieldValue(self::FIELD_USERNAME, $value);
    }

    public function setStatus(mixed $value): self

    {
        return $this->setFieldValue(self::FIELD_STATUS, $value);
    }

    public function setCreatedAt(mixed $value): self
    {
        return $this->setFieldValue(self::FIELD_CREATED_AT, $value);
    }

    public function setUpdatedAt(mixed $value): self
    {
        return $this->setFieldValue(self::FIELD_UPDATED_AT, $value);
    }

    /**
     * @psalm-assert-if-true MetaData $array
     */
    public static function validateMetaData(array $array): bool
    {
        return isset($array['id']) && \is_int($array['id']) &&
            isset($array['first_name']) && \is_string($array['first_name']) &&
            isset($array['last_name']) && \is_string($array['last_name']) &&
            isset($array['username']) && \is_string($array['username']) &&
            isset($array['status']) && \is_string($array['status']);
    }

    public function save($cacheFlag = NULL)
    {
        if ($this->isNew()) {
            $this->setCreatedAt(\time());
        } else {
            $this->setUpdatedAt(\time());
        }

        return parent::save($cacheFlag);
    }

}
