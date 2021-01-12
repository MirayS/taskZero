<?php

declare(strict_types=1);

namespace App\Validator;


use App\Entity\ProductData;

class PriceValidator implements ValidatorInterface
{
    private float $maxPrice = 1000;
    private string $errorMessage = "The price more than {maxPrice}";

    public function validate(ProductData $product): ?string
    {
        if ($product->getPrice() > $this->maxPrice) {
            return $this->getError();
        }

        return null;
    }

    private function getError(): string
    {
        return str_replace('{maxPrice}', $this->maxPrice, $this->errorMessage);
    }
}