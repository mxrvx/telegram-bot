<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use MXRVX\Schema\System\Settings\SchemaConfigInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class App extends Telegram
{
    public const NAMESPACE = 'mxrvx-telegram-bot';

    public SchemaConfigInterface $config;

    /** @var string[] */
    protected static array $listenerClasses = [];

    /** @var string[] */
    protected static array $callbackClasses = [];

    public function __construct(public \modX $modx)
    {
        $this->config = Config::make($modx->config);

        if ($this->config->getSetting('log_active')?->getBoolValue()) {
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

    public static function injectDependencies(\modX $modx): void
    {
        self::injectTelegram($modx);
    }

    public static function injectTelegram(\modX $modx): void
    {
        self::addListenerClasses([
            Listeners\MessageListener::class,
            Listeners\ChatMemberListener::class,
        ]);
    }

    public static function getNamespaceCamelCase(): string
    {
        return \lcfirst(\str_replace(' ', '', \ucwords(\str_replace('-', ' ', App::NAMESPACE))));
    }

    public static function addListenerClass(string $className): void
    {
        if (!\in_array($className, self::$listenerClasses, true)) {
            self::$listenerClasses[] = $className;
        }
    }

    /**
     * @param class-string[] $classNames
     */
    public static function addListenerClasses(array $classNames): void
    {
        foreach ($classNames as $className) {
            self::addListenerClass($className);
        }
    }

    public static function addCallbackClass(string $className): void
    {
        if (!\in_array($className, self::$callbackClasses, true)) {
            self::$callbackClasses[] = $className;
        }
    }

    /**
     * @param class-string[] $classNames
     */
    public static function addCallbackClasses(array $classNames): void
    {
        foreach ($classNames as $className) {
            self::addCallbackClass($className);
        }
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

    public function handleCallbacks(?Update $update = null): ?ServerResponse
    {
        foreach (self::$callbackClasses as $class) {
            if (\is_a($class, CallbackInterface::class, true)) {
                try {
                    $callback = new $class($this, $update);
                    $response = $callback->execute();
                    if ($response instanceof ServerResponse) {
                        return $response;
                    }
                } catch (Exceptions\CallbackNothingToHandleException $e) {
                } catch (\Throwable $e) {
                    TelegramLog::error($e->getMessage(), [$e->getFile()]);
                }
            }
        }

        return null;
    }

    public function handleListeners(?Update $update = null): ?ServerResponse
    {
        foreach (self::$listenerClasses as $class) {
            if (\is_a($class, ListenerInterface::class, true)) {
                try {
                    $listener = new $class($this, $update);
                    $listener->execute();
                } catch (Exceptions\ListenerNothingToHandleException $e) {
                } catch (\Throwable $e) {
                    TelegramLog::error($e->getMessage(), [$e->getFile()]);
                }
            }
        }

        return null;
    }

    public function executeCommandInstance(Command $command, Update $update): ?ServerResponse
    {
        try {
            if (!$command->isEnabled()) {
                return null;
            }

            $response = $command->setUpdate($update)->preExecute();
            $this->last_command_response = $response;

            return $response;

        } catch (Exceptions\CommandNothingToHandleException $e) {
        } catch (\Throwable $e) {
            TelegramLog::error($e->getMessage(), [$e->getFile()]);
        }

        return null;
    }
}
