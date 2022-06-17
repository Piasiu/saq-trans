<?php
namespace Saq\Trans\Adapter;

use Saq\Trans\AdapterInterface;

class ArrayAdapter implements AdapterInterface
{
    /**
     * @inheritDoc
     */
    public function loadFromFile(string $filePath): array
    {
        $filePath .= '.php';

        if (file_exists($filePath))
        {
            $data = include($filePath);

            if (is_array($data))
            {
                return $data;
            }
        }

        return [];
    }
}