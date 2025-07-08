<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Commands;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/** @psalm-suppress PropertyNotSetInConstructor */
class CallbackqueryCommand extends \Longman\TelegramBot\Commands\SystemCommands\CallbackqueryCommand
{
    public function execute(): ServerResponse
    {
        $callback = $this->getCallbackQuery();
        $message = $callback->getMessage();

        /** @var \MXRVX\Telegram\Bot\App $app */
        $app = $this->getTelegram();
        if ($data = \explode('::', $callback->getData())) {
            $command = \array_shift($data);
            if (\strpos($command, '/') === 0) {
                return $app->executeCommand(\substr($command, 1));
            }
        }

        return Request::sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $data,
        ]);
    }
}
