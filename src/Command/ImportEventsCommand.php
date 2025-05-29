<?php

namespace App\Command;

use App\service\FireCrawlerImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportEventsCommand extends Command
{
    protected static $defaultName = 'app:import-events';

    private FireCrawlerImporter $importer;

    public function __construct(FireCrawlerImporter $importer)
    {
        parent::__construct();
        $this->importer = $importer;
    }

    protected function configure()
    {
        $this->setName('app:import-events');
        $this->setDescription('Importe les événements depuis ladecadanse.ch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importer->importFromDecadanse();

        $output->writeln('Événements importés avec succès !');

        return Command::SUCCESS;
    }
}
