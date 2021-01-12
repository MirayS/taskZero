<?php

declare(strict_types=1);

namespace App\Validator;


use App\Entity\ProductData;

interface ValidatorInterface
{
    public function validate(ProductData $product): ?string;
}