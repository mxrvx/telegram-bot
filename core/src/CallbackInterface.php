<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot;

use Longman\TelegramBot\Entities\ServerResponse;

interface CallbackInterface
{
    public function execute(): ?ServerResponse;
}
