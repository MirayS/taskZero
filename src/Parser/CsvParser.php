<?php

declare(strict_types=1);

namespace App\Parser;


class CsvParser implements ParserInterface
{
    public function parse(string $filePath): \Generator
    {
        $row = 0;
        try  {
            $handle = fopen($filePath, 'r');
            if ($handle === false) {
                throw new FileNotFoundException();
            }
        } catch (\Exception $exception) {
            throw new FileNotFoundException();
        }


        try {
            while (($data = fgetcsv($handle)) !== false) {
                if ($row !== 0) {
                    yield $data;
                }
                $row++;
            }
        } finally {
            fclose($handle);
        }
    }
}