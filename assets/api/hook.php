<?php

/** @var \modX $modx */
/** @var \DI\Container $container */
/** @psalm-suppress MissingFile */
require dirname(__DIR__, 2) . '/core/autoloader.php';

try {
    if ($app = $container->get(\MXRVX\Telegram\Bot\App::class)) {
        $app->handle();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    $modx->log(\modX::LOG_LEVEL_ERROR, \sprintf('Error: %s', $e->getMessage()));
} catch (Throwable $e) {
    $modx->log(\modX::LOG_LEVEL_ERROR, \sprintf('Error: %s File: %s', $e->getMessage(), $e->getFile()));
}
