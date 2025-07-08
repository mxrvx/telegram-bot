<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Console\Command;

use MXRVX\ORM\MODX\Entities\Namespaces;
use MXRVX\Telegram\Bot\App;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends Command
{
    protected static $defaultName = 'remove';
    protected static $defaultDescription = 'Remove `' . App::NAMESPACE . '` extra from MODX';

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Run Migrations</info>');

        $command = [
            'command' => 'migration:down',
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
            $output->writeln(\sprintf(
                '<info>Command `%s` executed successfully</info>',
                $command['command'],
            ));
        } else {
            $output->writeln(\sprintf(
                '<error>Command `%s` failed with return code `%s`</error>',
                $command['command'],
                $returnCode,
            ));

            return $returnCode;
        }

        $corePath = MODX_CORE_PATH . 'components/' . App::NAMESPACE;
        if (\is_dir($corePath)) {
            \unlink($corePath);
            $output->writeln('<info>Removed symlink for `core`</info>');
        }
        $assetsPath = MODX_ASSETS_PATH . 'components/' . App::NAMESPACE;
        if (\is_dir($assetsPath)) {
            \unlink($assetsPath);
            $output->writeln('<info>Removed symlink for `assets`</info>');
        }

        if ($namespace = Namespaces::findByPK(App::NAMESPACE)) {
            $settings = $namespace->SystemSettings ?? [];
            $result = Namespaces::transact(static function () use ($namespace, $settings): bool {
                $namespace->delete();
                foreach ($settings as $setting) {
                    $setting->delete();
                }
                return true;
            });

            if ($result) {
                $output->writeln(\sprintf('<info>Removed namespace `%s`</info>', $namespace->name));
            }
        }

        \MXRVX\Autoloader\App::cacheManager()->clearCache();
        $output->writeln('<info>Cleared MODX cache</info>');

        return Command::SUCCESS;
    }
}
