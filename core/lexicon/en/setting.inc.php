<?php

declare(strict_types=1);

/** @psalm-suppress MissingFile */
require_once MODX_CORE_PATH . 'vendor/autoload.php';

use MXRVX\Telegram\Bot\App;
use MXRVX\Telegram\Bot\Tools\Lexicon;

/** @var array<array-key, array<array-key,string>|string> $_tmp */
$_tmp = [
    'bot_token' => 'Api ключ бота',
    'bot_token_desc' => '',
    'bot_username' => 'Имя бота',
    'bot_username_desc' => '',
    'hook_url' => 'Адрес вебхука',
    'hook_url_desc' => '',
    'log_active' => 'Активировать лог',
    'log_active_desc' => '',
];

/** @var array<array-key, string> $_tmp */
$_tmp = Lexicon::make($_tmp, 'setting_' . App::NAMESPACE);

/** @var array<array-key, string> $_lang */
if (isset($_lang)) {
    $_lang = \array_merge($_lang, $_tmp);
}

unset($_tmp);
