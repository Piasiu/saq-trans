<?php
namespace Saq\Trans;

interface AdapterInterface
{
    /**
     * @param string $fileName
     * @return array
     */
    public function loadFromFile(string $fileName): array;
}