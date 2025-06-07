<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Commands;

use Longman\TelegramBot\Entities\ServerResponse;

/** @psalm-suppress PropertyNotSetInConstructor */
class StartCommand extends Command
{
    protected $name = 'start';
    protected $description = 'Запуск бота';
    protected $usage = '/start';

    public function execute(): ServerResponse
    {
        $user = $this->getMessage()->getFrom();
        $data = [
            'Привет, ' . ($user->getFirstName()) . '!',
            'Используй /help, чтобы увидеть все доступные команды.',
        ];

        return $this->replyToChat(\implode(PHP_EOL . PHP_EOL, $data));
    }
}
