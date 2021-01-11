<?php


namespace App\Entity;


class ParseResult
{
    private bool $status;
    private array $errors;
    private array $parsedData;
    private int $itemsProcessed;

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getParsedData(): array
    {
        return $this->parsedData;
    }

    /**
     * @param array $parsedData
     */
    public function setParsedData(array $parsedData): void
    {
        $this->parsedData = $parsedData;
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

    public function getItemsWithErrorCount() : int {
        return $this->itemsProcessed - count($this->getParsedData());
    }

    /**
     * @param int $itemsProcessed
     */
    public function setItemsProcessed(int $itemsProcessed): void
    {
        $this->itemsProcessed = $itemsProcessed;
    }
}