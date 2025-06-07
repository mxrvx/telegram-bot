<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot;

use MXRVX\Schema\System\Settings;
use MXRVX\Schema\System\Settings\SchemaConfig;

class Config extends SchemaConfig
{
    public static function make(array $config): SchemaConfig
    {
        $schema = Settings\Schema::define(App::NAMESPACE)
            ->withSettings(
                [
                    Settings\Setting::define(
                        key: 'bot_token',
                        value: '7555550000:BBGlgudj7aaia3yh9rcn6w_t1yAtZEJcEGo',
                        xtype: 'textfield',
                        typecast: Settings\TypeCaster::STRING,
                    ),
                    Settings\Setting::define(
                        key: 'bot_username',
                        value: 'name_telegram_bot',
                        xtype: 'textfield',
                        typecast: Settings\TypeCaster::STRING,
                    ),
                    Settings\Setting::define(
                        key: 'hook_url',
                        value: \trim(MODX_SITE_URL, '/') . '/assets/components/mxrvx-telegram-bot/api/hook.php',
                        xtype: 'textfield',
                        typecast: Settings\TypeCaster::STRING,
                    ),
                    Settings\Setting::define(
                        key: 'log_active',
                        value: false,
                        xtype: 'combo-boolean',
                        typecast: Settings\TypeCaster::BOOLEAN,
                    ),
                ],
            );
        return SchemaConfig::define($schema)->withConfig($config);
    }
}
