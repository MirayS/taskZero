<?php


namespace App\Validator;


use App\Entity\ProductData;

class PriceAndCountValidator implements ValidatorInterface
{

    public function validate(ProductData $product): bool
    {
        if ($product->getPrice() < 5 && $product->getStock() < 10)
            return false;

        return true;
    }
}