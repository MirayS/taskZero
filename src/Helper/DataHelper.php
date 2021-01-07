<?php
declare(strict_types=1);

namespace App\Helper;


class DataHelper
{
    /**
     * @var ExchangeRateHelper
     */
    private ExchangeRateHelper $exchangeRateHelper;

    public function __construct(ExchangeRateHelper $exchangeRateHelper) {

        $this->exchangeRateHelper = $exchangeRateHelper;
    }

    public function parseString(string $data):string {
        return $data;
    }

    public function parsePriceInDollars(string $data):float {
        if ($data[0] == "$") {
            return floatval(substr($data, 1));
        }

        if (!is_numeric($data))
            throw new \Exception("Invalid type");

        $exchangeRate = $this->exchangeRateHelper->getRates(ExchangeRateHelper::GBP_CURRENCY, ExchangeRateHelper::USD_CURRENCY);
        if ($exchangeRate == null)
            throw new \Exception("Get exchange rate error");

        $gbpValue = floatval($data);
        return $gbpValue * $exchangeRate;
    }

    public function parseCount(string $data):int {
        if (!is_numeric($data))
            throw new \Exception("Invalid type");
        return intval($data);
    }

    public function parseBool(string $data) :bool{
        if (!isset($data))
            return false;

        if(mb_strtolower($data) == "yes")
            return true;

        return false;
    }
}