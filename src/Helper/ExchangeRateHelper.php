<?php

declare(strict_types=1);

namespace App\Helper;


class ExchangeRateHelper
{
    private array $cache = [];

    public const USD_CURRENCY = "USD";
    public const GBP_CURRENCY = "GBP";

    public function getRates(string $from, string $to): ?float
    {
        return $this->getRatesFromCache($from, $to);
    }

    private function getRatesFromCache(string $from, string $to): ?float
    {
        if (isset($this->cache[$from]) && isset($this->cache[$from][$to])) {
            return $this->cache[$from][$to];
        }
        $result = $this->getRatesFromApi($from, $to);
        if ($result != null) {
            $this->cache[$from][$to] = $result;
        }

        return $result;
    }

    private function getRatesFromApi(string $from, string $to): ?float
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.exchangeratesapi.io/latest?symbols=${to}&base=${from}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsonData = json_decode($output);
        if (isset($jsonData->rates->$to)) {
            return $jsonData->rates->$to;
        }

        return null;
    }
}