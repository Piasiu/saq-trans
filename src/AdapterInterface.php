<?php
namespace Saq\Trans;

interface AdapterInterface
{
    /**
     * @param string $filePath
     * @return array
     */
    public function loadFromFile(string $filePath): array;
}