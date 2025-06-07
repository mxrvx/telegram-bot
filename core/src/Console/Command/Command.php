<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Console\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use MXRVX\Telegram\Bot\App;

abstract class Command extends SymfonyCommand
{
    public const SUCCESS = SymfonyCommand::SUCCESS;
    public const FAILURE = SymfonyCommand::FAILURE;
    public const INVALID = SymfonyCommand::INVALID;

    public function __construct(protected App $app, ?string $name = null)
    {
        parent::__construct($name);
    }
}
