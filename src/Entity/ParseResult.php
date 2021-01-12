<?php

declare(strict_types=1);

namespace App\Entity;


class ParseResult
{
    private bool $status;
    private array $errors;
    private array $parsedData;
    private int $itemsProcessed;

    public function __construct(bool $status, int $itemsProcessed, array $parsedData, array $errors)
    {
        $this->status = $status;
        $this->itemsProcessed = $itemsProcessed;
        $this->parsedData = $parsedData;
        $this->errors = $errors;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getParsedData(): array
    {
        return $this->parsedData;
    }

    /**
     * @return int
     */
    public function getItemsProcessed(): int
    {
        return $this->itemsProcessed;
    }

    public function getItemsParsedCount(): int
    {
        return count($this->getParsedData());
    }

    public function getItemsWithErrorCount(): int
    {
        return $this->itemsProcessed - count($this->getParsedData());
    }
}