<?php
namespace Ebb\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Psr\Log\NullLogger;
use Ebb\Generator;
use Ebb\RestApi;

class GenerateCommand extends Command
{
    protected function configure()
    {
        $this->setName('generate')
            ->addArgument('api', null, 'URL to rest API, including site and api key)')
            ->addOption('flush', null, null, 'Do not use cached API requests')
            ->addOption('out', null, InputOption::VALUE_REQUIRED, 'Write the schema to OUT')
            ->addOption('dry-run', null, null, 'Run the generator but do not produce any output');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = new RestApi(
            $input->getArgument('api'),
            new FilesystemCache,
            $input->getOption('flush')
        );
        $log = new ConsoleLogger($output);
        $generator = new Generator(
            $api,
            $log
        );
        $log->info('Starting generator...');
        $generator->run();
        if (!$input->getOption('dry-run')) {
            if ($input->getOption('out')) {
                file_put_contents($input->getOption('out'), $generator->dump());
            } else {
                $output->write($generator->dump());
            }
        }
        $log->info('Start generating boilerplate...');
        $log->info('Finished generating boilerplate.');
    }
}
