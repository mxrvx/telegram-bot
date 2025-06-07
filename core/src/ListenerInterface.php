<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot;

interface ListenerInterface
{
    public function execute(): void;
}
