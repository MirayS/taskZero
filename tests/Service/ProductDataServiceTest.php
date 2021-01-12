<?php


namespace App\Tests\Service;


use App\Helper\DataHelper;
use App\Helper\ExchangeRateHelper;
use App\Parser\CsvParser;
use App\Parser\FileNotFoundException;
use App\Repository\ProductDataRepository;
use App\Service\ProductDataService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\TestFixture\C;

class ProductDataServiceTest extends TestCase
{
    public function testFileNotFoundException() {

        $repository = $this->createMock(ProductDataRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $dataHelper = $this->createMock(DataHelper::class);

        $productDataService = new ProductDataService($repository, $entityManager,$dataHelper);

        $this->expectException(FileNotFoundException::class);
        $productDataService->parseDataFromFile("fileNotFound.csv", new CsvParser());
    }

    public function testValidParse() {
        $repository = $this->createMock(ProductDataRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $exchangeRateHelper = new ExchangeRateHelper();

        $dataHelper = new DataHelper($exchangeRateHelper);

        $productDataService = new ProductDataService($repository, $entityManager,$dataHelper);

        $result = $productDataService->parseDataFromFile("tests/Service/validData.csv", new CsvParser());

        $this->assertEquals(2, $result->getItemsProcessed());
        $this->assertEquals(2, $result->getItemsParsedCount());
        $this->assertEquals(0, $result->getItemsWithErrorCount());

    }


}