<?php

namespace Kenjiefx\StrawberryScratch\Commands;
use Kenjiefx\StrawberryScratch\Services\PageSyncService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'sync:pages')]
class SyncPages extends Command {
    protected static $defaultDescription = 'Sync pages data into StrawberryScratch';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        PageSyncService::sync(ROOT . '/pages');
        PageSyncService::types();
        echo 'test extension external command executed';
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Sync pages data into StrawberryScratch.');
    }
}