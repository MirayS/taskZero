<?php

declare(strict_types=1);

namespace App\Validator;


use App\Entity\ProductData;

class PriceAndCountValidator implements ValidatorInterface
{
    private float $minPrice = 5;
    private float $minCount = 10;
    private string $errorMessage = "The price less than {minPrice} && stock less than {minCount}";


    public function validate(ProductData $product): ?string
    {
        if ($product->getPrice() < $this->minPrice && $product->getStock() < $this->minCount) {
            return $this->getErrorMessage();
        }

        return null;
    }

    private function getErrorMessage(): string
    {
        $error = $this->errorMessage;
        $error = str_replace("{minPrice}", $this->minPrice, $error);
        $error = str_replace("{minCount}", $this->minCount, $error);

        return $error;
    }
}