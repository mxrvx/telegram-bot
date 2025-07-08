<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Console\Command;

use MXRVX\ORM\MODX\Entities\Namespaces;
use MXRVX\ORM\MODX\Entities\SystemSetting;
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

        $srcPath = MODX_CORE_PATH . 'vendor/' . (string) \preg_replace('/-/', '/', App::NAMESPACE, 1);
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

        if (!Namespaces::findByPK(App::NAMESPACE)) {
            $namespace = Namespaces::make([
                'name' => App::NAMESPACE,
                'path' => '{core_path}components/' . App::NAMESPACE . '/',
                'assets_path' => '',
            ]);
            if ($namespace->save()) {
                $output->writeln(\sprintf('<info>Created namespace `%s`</info>', $namespace->name));
            }
        }

        /** @var array{key: string, value: mixed} $row */
        foreach ($app->config->getSettingsArray() as $row) {
            if (!SystemSetting::findByPK($row['key'])) {
                $setting = SystemSetting::make($row);
                if ($setting->save()) {
                    $output->writeln(\sprintf('<info>Created system setting `%s`</info>', $setting->key));
                }
            }
        }

        $output->writeln('<info>Run Migrations</info>');

        $command = [
            'command' => 'migration:up',
            'params' => [
                '--namespace' => App::NAMESPACE,
            ],
        ];

        try {
            $returnCode = $this->runCommand(
                command: $command['command'],
                params: $command['params'],
                output: $output,
            );
        } catch (\Throwable $e) {
            $returnCode = Command::FAILURE;
            $output->writeln(\sprintf('<error>Exception occurred: %s</error>', $e->getMessage()));
        }

        if ($returnCode === Command::SUCCESS) {
            $output->writeln(\sprintf('<info>Command `%s` executed successfully</info>', $command['command']));
        } else {
            $output->writeln(\sprintf(
                '<error>Command `%s` failed with return code `%s`</error>',
                $command['command'],
                $returnCode,
            ));

            return $returnCode;
        }

        \MXRVX\Autoloader\App::cacheManager()->clearCache();
        $output->writeln('<info>Cleared MODX cache</info>');

        return self::SUCCESS;
    }
}
