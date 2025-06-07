<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MXRVX\Telegram\Bot\App;

class InstallCommand extends Command
{
    protected static $defaultName = 'install';
    protected static $defaultDescription = 'Install `' . App::NAMESPACE . '` extra for MODX';

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $app = $this->app;
        $modx = $this->app->modx;

        $srcPath = MODX_CORE_PATH . 'vendor/' . \preg_replace('/-/', '/', App::NAMESPACE, 1);
        $corePath = MODX_CORE_PATH . 'components/' . App::NAMESPACE;
        if (!\is_dir($corePath)) {
            \symlink($srcPath . '/core', $corePath);
            $output->writeln('<info>Created symlink for `core`</info>');
        }

        $assetsPath = MODX_ASSETS_PATH . 'components/' . App::NAMESPACE;
        if (!\is_dir($assetsPath)) {
            \symlink($srcPath . '/assets', $assetsPath);
            $output->writeln('<info>Created symlink for `assets`</info>');
        }

        if (!$modx->getObject(\modNamespace::class, ['name' => App::NAMESPACE])) {
            /** @var \modNamespace $namespace */
            $namespace = $modx->newObject(\modNamespace::class);
            $namespace->fromArray(
                [
                    'name' => App::NAMESPACE,
                    'path' => '{core_path}components/' . App::NAMESPACE . '/',
                    'assets_path' => '',
                ],
                '',
                true,
            );
            $namespace->save();
            $output->writeln(\sprintf('<info>Created namespace `%s`</info>', App::NAMESPACE));
        }


        /** @var array{key: string, value: mixed} $row */
        foreach ($app->config->getSettingsArray() as $row) {
            if (!$modx->getObject(\modSystemSetting::class, $row['key'])) {
                /** @var \modSystemSetting $setting */
                $setting = $modx->newObject(\modSystemSetting::class);
                $setting->fromArray($row, '', true);
                $setting->save();
                $output->writeln(\sprintf('<info>Created system setting `%s`</info>', $row['key']));
            }
        }

        $modx->getCacheManager()->refresh();

        $output->writeln('<info>Cleared MODX cache</info>');

        return Command::SUCCESS;
    }
}
