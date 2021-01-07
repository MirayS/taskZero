<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\ProductData;
use App\Helper\ExchangeRateHelper;
use App\Parser\CsvParser;
use App\Parser\FileNotFoundException;
use App\Repository\ProductDataRepository;
use App\Service\ProductDataService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ParseCsvCommand extends Command
{
    protected static $defaultName = 'app:parse-csv';
    /**
     * @var ProductDataService
     */
    private ProductDataService $productDataService;

    public function __construct(string $name = null, ProductDataService $productDataService)
    {
        parent::__construct($name);

        $this->productDataService = $productDataService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Parse CSV file to database')
            ->addArgument('file', InputArgument::REQUIRED, 'path to the file to parse')
            ->addArgument('test', InputArgument::OPTIONAL, 'Start in test mode')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');
        $test = $input->getArgument('test');

        if (!file_exists($file)) {
            $io->error('File not exists');
            return Command::FAILURE;
        }

        $result = $this->productDataService->parseDataFromFile($file, new CsvParser());
        if (!isset($test))
            $this->productDataService->saveRangeToDataBase($result);

        return Command::SUCCESS;
    }
}
