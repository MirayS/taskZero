<?php


namespace App\Parser;


interface ParserInterface
{
    public function parse(string $filePath) : \Generator;
}