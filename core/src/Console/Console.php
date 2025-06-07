<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\ListCommand;
use MXRVX\Telegram\Bot\App;
use MXRVX\Telegram\Bot\Console\Command\InstallCommand;
use MXRVX\Telegram\Bot\Console\Command\RemoveCommand;
use MXRVX\Telegram\Bot\Console\Command\HookCommand;

class Console extends Application
{
    public function __construct(protected App $app)
    {
        parent::__construct(App::NAMESPACE);
    }

    protected function getDefaultCommands(): array
    {
        return [
            new ListCommand(),
            new InstallCommand($this->app),
            new RemoveCommand($this->app),
            new HookCommand($this->app),
        ];
    }
}
