<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Console\Command;

use MXRVX\Telegram\Bot\App;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class HookCommand extends Command
{
    /** @var array<string> */
    protected array $actions = ['install', 'remove'];

    protected static $defaultName = 'hook';
    protected static $defaultDescription = 'Webhook `' . App::NAMESPACE . '` extra from MODX';

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $action = (string) $input->getOption('action');
        if (empty($action) || !\in_array($action, $this->actions)) {
            $output->writeln(\sprintf('<error>Error: You need to specify an action, e.g. run --action=%s</error>', \implode(' | ', $this->actions)));
            return Command::FAILURE;
        }

        try {
            $hookUrl = $this->app->getHookUrl();
            $output->writeln(\sprintf('<info>Hook URL: `%s`</info>', $hookUrl));

            if ($action === 'install') {

                $result = $this->app->setWebhook($hookUrl);
                if ($result->isOk()) {
                    $output->writeln(\sprintf('<info>%s</info>', $result->getDescription()));
                } else {
                    $output->writeln(\sprintf('<error>%s</error>', $result->getDescription()));
                }
            } elseif ($action === 'remove') {
                $result = $this->app->deleteWebhook();
                if ($result->isOk()) {
                    $output->writeln(\sprintf('<info>%s</info>', $result->getDescription()));
                } else {
                    $output->writeln(\sprintf('<error>%s</error>', $result->getDescription()));
                }
            }

        } catch (\Throwable $e) {
            $output->writeln(\sprintf('<error>%s</error>', $e->getMessage()));
        }


        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addOption('action', null, InputOption::VALUE_REQUIRED, 'Webhook action to perform ' . \implode(' | ', $this->actions));
    }
}
