# System Settings Schema Builder for MODX Revolution

## Installation and configuration

Install the package via composer:

```
composer require --dev mxrvx/schema-system-settings
```
[![PHP](https://img.shields.io/packagist/php-v/mxrvx/schema-system-settings.svg?style=flat-square&logo=php)](https://packagist.org/packages/mxrvx/schema-system-settings)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mxrvx/schema-system-settings.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/mxrvx/schema-system-settings)
[![License](https://img.shields.io/packagist/l/mxrvx/schema-system-settings.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/mxrvx/schema-system-settings.svg?style=flat-square)](https://packagist.org/packages/mxrvx/schema-system-settings)

## Configuration

```php
declare(strict_types=1);

use MXRVX\Schema\System\Settings;
use MXRVX\Schema\System\Settings\TypeCaster;

require_once \dirname(__DIR__, 1) . '\vendor\autoload.php';

$schema = Settings\Schema::define('app-namespace')
    ->withSettings(
        [
            Settings\Setting::define(
                key: 'key1',
                value: 1,
                xtype: 'combo-boolean',
                area: 'system',
                typecast: TypeCaster::BOOLEAN,
            ),
            Settings\Setting::define(
                key: 'key2',
                value: 'string',
                xtype: 'textfield',
                area: 'system',
                typecast: TypeCaster::STRING,
            ),
            Settings\Setting::define(
                key: 'key3',
                value: '312341241234',
                xtype: 'textfield',
                area: 'system',
                typecast: TypeCaster::INTEGER,
            ),
            Settings\Setting::define(
                key: 'key4',
                value: ['services' => ['github', 'bitbucket', 'google']],
                xtype: 'textfield',
                area: 'services',
                typecast: TypeCaster::ARRAY,
            ),
            Settings\Setting::define(
                key: 'key5',
                value: ['services' => ['github', 'bitbucket', 'google']],
                xtype: 'textfield',
                area: 'services',
                typecast: TypeCaster::JSON,
            ),
            Settings\Setting::define(
                key: 'key6',
                value: '1,2,3',
                xtype: 'textfield',
                area: 'services',
                typecast: [TypeCaster::STRING, TypeCaster::LIST_INTEGER],
            ),
        ]
    );
```

We can now create config after defining the scheme

```php
/** @var \MXRVX\Schema\System\Settings\Schema $schema */
/** @var \MXRVX\Schema\System\Settings\SchemaConfig */
$schemaConfig = SchemaConfig::define($schema)->withConfig($config);
```

## Usage

```php
/** @var \MXRVX\Schema\System\Settings\SchemaConfig $schemaConfig */

//NOTE get setting value
$value = $schemaConfig->getSettingValue('key1');

//NOTE set setting value
$schemaConfig->setSettingValue('key1', $newValue);

//NOTE get all settings as array
var_export($schemaConfig->getSettingsArray(processValue: true));

array (
  'key1' => 
  array (
    'key' => 'app-namespace.key1',
    'value' => true,
    'xtype' => 'combo-boolean',
    'area' => 'system',
    'typecast' => 'MXRVX\\Schema\\System\\Settings\\TypeCasters\\BooleanCaster',
  ),
  'key2' => 
  array (
    'key' => 'app-namespace.key2',
    'value' => 'string',
    'xtype' => 'textfield',
    'area' => 'system',
    'typecast' => 'MXRVX\\Schema\\System\\Settings\\TypeCasters\\StringCaster',
  ),
  'key3' => 
  array (
    'key' => 'app-namespace.key3',
    'value' => 312341241234,
    'xtype' => 'textfield',
    'area' => 'system',
    'typecast' => 'MXRVX\\Schema\\System\\Settings\\TypeCasters\\IntegerCaster',
  ),
  'key4' => 
  array (
    'key' => 'app-namespace.key4',
    'value' => 
    array (
      'services' => 
      array (
        0 => 'github',
        1 => 'bitbucket',
        2 => 'google',
      ),
    ),
    'xtype' => 'textfield',
    'area' => 'services',
    'typecast' => 'MXRVX\\Schema\\System\\Settings\\TypeCasters\\ArrayCaster',
  ),
  'key5' => 
  array (
    'key' => 'app-namespace.key5',
    'value' => '{"services":["github","bitbucket","google"]}',
    'xtype' => 'textfield',
    'area' => 'services',
    'typecast' => 'MXRVX\\Schema\\System\\Settings\\TypeCasters\\JsonCaster',
  ),
  'key6' => 
  array (
    'key' => 'app-namespace.key6',
    'value' => 
    array (
      0 => 1,
      1 => 2,
      2 => 3,
    ),
    'xtype' => 'textfield',
    'area' => 'services',
    'typecast' => 
    array (
      0 => 'MXRVX\\Schema\\System\\Settings\\TypeCasters\\StringCaster',
      1 => 'MXRVX\\Schema\\System\\Settings\\TypeCasters\\ListIntegerCaster',
    ),
  ),
)

```

## Feedback

I will be glad to see your ideas, suggestions and questions in [issues](https://github.com/mxrvx/schema-system-settings/issues).
