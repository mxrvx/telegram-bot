<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Commands;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

/** @psalm-suppress PropertyNotSetInConstructor */
abstract class Command extends \Longman\TelegramBot\Commands\UserCommand
{
    /**
     * Execute command
     *
     * @throws TelegramException
     */
    abstract public function execute(): ServerResponse;
}
