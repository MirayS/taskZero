<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\ProductData;
use App\Helper\DataHelper;
use App\Helper\ExchangeRateHelper;
use App\Parser\ParserInterface;
use App\Repository\ProductDataRepository;
use App\Validator\PriceAndCountValidator;
use App\Validator\PriceValidator;
use App\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProductDataService
{
    /**
     * @var DataHelper
     */
    private DataHelper $dataHelper;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var ProductDataRepository
     */
    private ProductDataRepository $repository;

    public function __construct(ProductDataRepository $repository, EntityManagerInterface $em, DataHelper $dataHelper) {

        $this->dataHelper = $dataHelper;
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param string $fileName
     * @param ParserInterface $parser
     * @return ProductData[]
     */
    public function parseDataFromFile(string $fileName, ParserInterface $parser): array {
        $result = [];
        foreach ($parser->parse($fileName) as $parsedRow) {
            try {
                if (count($parsedRow) < 6)
                    continue;
                $productCode = $this->dataHelper->parseString($parsedRow[0]);
                if (count(array_filter($result, function($row) use($productCode) { return $row->getCode() == $productCode; })) != 0)
                    continue;

                $row = $this->fillEntity($this->getProductDataEntity($productCode), $parsedRow);
                if ($this->validateEntity($row, [
                    new PriceAndCountValidator(),
                    new PriceValidator(),
                ]))
                $result[] = $row;
            } catch (\Exception $exception) {

            }
        }
        return $result;
    }

    private function fillEntity(ProductData $productData, array $parsedRow):ProductData {
        $productData->setCode($parsedRow[0]);
        $productData->setName($parsedRow[1]);
        $productData->setDescription($parsedRow[2]);
        $productData->setStock($this->dataHelper->parseCount($parsedRow[3]));
        $productData->setPrice($this->dataHelper->parsePriceInDollars($parsedRow[4]));
        if ($this->dataHelper->parseBool($parsedRow[5]))
            $productData->setDiscontinued(new \DateTime());
        return $productData;
    }

    /**
     * @param ProductData $entity
     * @param ValidatorInterface[] $validators
     * @return bool
     */
    private function validateEntity(ProductData $entity, array $validators):bool {
        foreach ($validators as $validator)
        {
            if (!$validator->validate($entity))
                return false;
        }

        return true;
    }

    private function getProductDataEntity(string $productCode): ProductData {
        $row = $this->repository->findOneByCode($productCode);
        if ($row == null) {
            $row = new ProductData();
            $row->setAdded(new \DateTime());
            $row->setTimestamp(new \DateTime());
        }
        return $row;
    }

    /**
     * @param ProductData[] $products
     */
    public function saveRangeToDataBase(array $products) {
        foreach ($products as $product) {
            $this->em->persist($product);
        }
        $this->em->flush();
    }
}