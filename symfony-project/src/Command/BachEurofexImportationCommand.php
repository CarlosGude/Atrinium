<?php

namespace App\Command;

use App\Utils\BachBCEImportation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BachEurofexImportationCommand extends Command
{
    protected static $defaultName = 'app:bach-importation';
    /**
     * @var BachBCEImportation
     */
    private $bachBCEImportation;

    public function __construct(BachBCEImportation $bachBCEImportation)
    {
        parent::__construct();
        $this->bachBCEImportation = $bachBCEImportation;
    }

    protected function configure()
    {
        $this
            ->setDescription('Bach and incremental import data from BCE')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('================');
        $output->writeln('Bach Importation');
        $output->writeln('================');

        $dataImported = $this->bachBCEImportation->import();

        $output->writeln('Data imported from BCE '.$dataImported);
        return 0;
    }
}
