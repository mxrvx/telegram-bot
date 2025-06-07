<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Level;
use MXRVX\Schema\System\Settings\SchemaConfigInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class App extends Telegram
{
    public const NAMESPACE = 'mxrvx-telegram-bot';

    public SchemaConfigInterface $config;

    /** @var string[] */
    protected array $listenerClasses = [];

    /** @var string[] */
    protected array $callbackClasses = [];

    public function __construct(public \modX $modx)
    {
        $this->config = Config::make($modx->config);

        if ((bool) $this->config->getSettingValue('log_active')) {
            $dirLog = \dirname(__DIR__) . '/logs/';
            TelegramLog::initialize(
                new Logger('telegram_bot', [
                    (new StreamHandler($dirLog . 'debug.log', Level::Debug))->setFormatter(new LineFormatter(null, null, true)),
                    (new StreamHandler($dirLog . 'error.log', Level::Error))->setFormatter(new LineFormatter(null, null, true)),
                ]),
                new Logger('telegram_bot_updates', [
                    (new StreamHandler($dirLog . 'updates.log', Level::Info))->setFormatter(new LineFormatter('%message%' . PHP_EOL)),
                ]),
            );
        }

        parent::__construct((string) $this->config->getSettingValue('bot_token'), (string) $this->config->getSettingValue('bot_username'));
        $this->addCommandsPath(__DIR__ . '/Commands');
    }

    public function getHookUrl(): string
    {
        return (string) $this->config->getSettingValue('hook_url');
    }

    /**
     * Process bot Update request
     *
     * @throws TelegramException
     */
    public function processUpdate(Update $update): ServerResponse
    {
        $this->handleListeners($update);

        if ($response = $this->handleCallbacks($update)) {
            return $response;
        }

        return parent::processUpdate($update);
    }

    public function addListenerClass(string $className): void
    {
        if (!\in_array($className, $this->listenerClasses, true)) {
            $this->listenerClasses[] = $className;
        }
    }

    public function handleListeners(?Update $update = null): ?ServerResponse
    {
        foreach ($this->listenerClasses as $class) {
            if (\is_a($class, ListenerInterface::class, true)) {
                $listener = new $class($this, $update);
                $listener->execute();
            }
        }

        return null;
    }

    public function addCallbackClass(string $className): void
    {
        if (!\in_array($className, $this->callbackClasses, true)) {
            $this->callbackClasses[] = $className;
        }
    }

    public function handleCallbacks(?Update $update = null): ?ServerResponse
    {
        foreach ($this->callbackClasses as $class) {
            if (\is_a($class, CallbackInterface::class, true)) {
                $callback = new $class($this, $update);
                $response = $callback->execute();
                if ($response instanceof ServerResponse) {
                    return $response;
                }
            }
        }

        return null;
    }
}
