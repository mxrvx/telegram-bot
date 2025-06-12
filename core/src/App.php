<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use MXRVX\Autoloader\ClassLister;
use MXRVX\Schema\System\Settings\SchemaConfigInterface;
use MXRVX\Telegram\Bot\Listeners\ChatMemberListener;
use MXRVX\Telegram\Bot\Listeners\MessageListener;

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
        $this->addListenerClasses([MessageListener::class, ChatMemberListener::class]);

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

    public static function injectDependencies(\modX $modx): void
    {
        self::injectModelsWithNamespace($modx);
    }

    public static function getNamespaceCamelCase(): string
    {
        return \lcfirst(\str_replace(' ', '', \ucwords(\str_replace('-', ' ', App::NAMESPACE))));
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

    /**
     * @param class-string[] $classNames
     */
    public function addListenerClasses(array $classNames): void
    {
        foreach ($classNames as $className) {
            $this->addListenerClass($className);
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

    /**
     * @param class-string[] $classNames
     */
    public function addCallbackClasses(array $classNames): void
    {
        foreach ($classNames as $className) {
            $this->addCallbackClass($className);
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

    private static function injectModelsWithNamespace(\modX $modx): void
    {
        $baseNamespace = \substr(self::class, 0, (int) \strrpos(self::class, '\\'));
        $modelNamespace = \sprintf('%s\Models\\', $baseNamespace);
        $modelPath = MODX_CORE_PATH . 'components/' . self::NAMESPACE . '/src/Models/' . self::NAMESPACE . '/' . self::NAMESPACE . '/';
        $modelPrefix = self::getNamespaceCamelCase();

        /** @var array<int, class-string> $namespaceClasses */
        $namespaceClasses = ClassLister::findByRegex('/^' . \preg_quote($modelNamespace, '/') . '(?!.*_mysql$).+$/');
        foreach ($namespaceClasses as $namespaceClass) {
            if (isset($modx->map[$namespaceClass])) {
                continue;
            }

            $shortClassName = \substr($namespaceClass, (int) \strrpos($namespaceClass, '\\') + 1);
            $legacyClassName = $modelPrefix . $shortClassName;

            if (!isset($modx->map[$legacyClassName])) {
                /** @psalm-suppress DeprecatedMethod */
                $modx->loadClass($legacyClassName, $modelPath, true, false);
            }

            if (isset($modx->map[$legacyClassName])) {
                $modx->map[$namespaceClass] = $modx->map[$legacyClassName];
            }
        }
    }
}
