<?php


namespace App\Helper;


class ExchangeRateHelper
{
    public const USD_CURRENCY = "USD";
    public const GBP_CURRENCY = "GBP";

    public function getRates(string $from, string $to): ?float {
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