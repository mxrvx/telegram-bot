<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Commands;

use Longman\TelegramBot\Entities\ServerResponse;

/** @psalm-suppress PropertyNotSetInConstructor */
class HelpCommand extends Command
{
    protected $name = 'help';
    protected $description = 'Вывод списка доступных команд';
    protected $usage = '/help';
    protected $private_only = true;

    public function execute(): ServerResponse
    {
        $data = [
            'Доступные команды:',
            '',
        ];

        /** @var Command[] $commands */
        $commands = $this->telegram->getCommandsList();
        foreach ($commands as $command) {
            if ($command->showInHelp() && $command->getUsage()) {
                $data[] = $command->getUsage() . ' ' . $command->getDescription();
            }
        }

        return $this->replyToChat(\implode(PHP_EOL, $data));
    }
}
