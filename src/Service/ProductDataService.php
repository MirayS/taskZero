<?php

declare(strict_types=1);

namespace App\Service;


use App\Entity\ParseResult;
use App\Entity\ProductData;
use App\Helper\DataHelper;
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

    public function __construct(ProductDataRepository $repository, EntityManagerInterface $em, DataHelper $dataHelper)
    {
        $this->dataHelper = $dataHelper;
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param string $fileName
     * @param ParserInterface $parser
     * @return ParseResult
     */
    public function parseDataFromFile(string $fileName, ParserInterface $parser): ParseResult
    {
        $result = [];
        $errors = [];
        $itemsProcessed = 0;

        foreach ($parser->parse($fileName) as $key => $parsedRow) {
            $itemsProcessed++;
            $productCode = $this->dataHelper->parseString($parsedRow[0]);
            try {
                if (count($parsedRow) < 6) {
                    throw new \Exception("Not enough columns");
                }
                if (count(
                        array_filter(
                            $result,
                            function ($row) use ($productCode) {
                                return $row->getCode() == $productCode;
                            }
                        )
                    ) != 0) {
                    throw new \Exception("Product code ${productCode} already exists");
                }

                $row = $this->fillEntity($this->getProductDataEntity($productCode), $parsedRow);
                if (($error = $this->validateEntity(
                        $row,
                        [
                            new PriceAndCountValidator(),
                            new PriceValidator(),
                        ]
                    )) != null) {
                    throw new \Exception($error);
                }
                $result[] = $row;
            } catch (\Exception $exception) {
                $errors[] = $key." => [${productCode}] ".$exception->getMessage();
            }
        }

        return new ParseResult(true, $itemsProcessed, $result, $errors);
    }

    private function fillEntity(ProductData $productData, array $parsedRow): ProductData
    {
        $productData->setName($parsedRow[1]);
        $productData->setDescription($parsedRow[2]);
        $productData->setStock($this->dataHelper->parseCount($parsedRow[3]));
        $productData->setPrice($this->dataHelper->parsePriceInDollars($parsedRow[4]));
        if ($this->dataHelper->parseBool($parsedRow[5])) {
            $productData->setDiscontinued(new \DateTime());
        }

        return $productData;
    }

    /**
     * @param ProductData $entity
     * @param ValidatorInterface[] $validators
     * @return string|null
     */
    private function validateEntity(ProductData $entity, array $validators): ?string
    {
        foreach ($validators as $validator) {
            if (($error = $validator->validate($entity)) != null) {
                return $error;
            }
        }

        return null;
    }

    private function getProductDataEntity(string $productCode): ProductData
    {
        $row = $this->repository->findOneByCode($productCode);
        if ($row == null) {
            $row = new ProductData($productCode);
            $row->setAdded(new \DateTime());
        }

        return $row;
    }

    /**
     * @param ProductData[] $products
     */
    public function saveRangeToDataBase(array $products)
    {
        foreach ($products as $product) {
            $this->em->persist($product);
        }
        $this->em->flush();
    }
}