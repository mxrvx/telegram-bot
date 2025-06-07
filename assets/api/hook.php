<?php

use MXRVX\Telegram\Bot\App;

$file = dirname(__DIR__,2) . '/core/bootstrap.php';
if (file_exists($file)) {
    require $file;
} else {
    exit('Could not load Bootstrap');
}

/** @var \modX $modx */
$app = $modx->services[App::class] ??= new App($modx);

try {
    if ($app instanceof App) {
        $app->handle();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    $modx->log(\modX::LOG_LEVEL_ERROR, \sprintf('Error: %s', $e->getMessage()));
} catch (Throwable $e) {
    $modx->log(\modX::LOG_LEVEL_ERROR, \sprintf('Error: %s File: %s', $e->getMessage(), $e->getFile()));
}
