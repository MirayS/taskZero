<?php


namespace App\Validator;


use App\Entity\ProductData;

class PriceValidator implements ValidatorInterface
{

    public function validate(ProductData $product): bool
    {
        if ($product->getPrice() > 1000)
            return false;
        return true;
    }
}