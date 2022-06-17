<?php
namespace Saq\Trans\Adapter;

use Saq\Trans\AdapterInterface;

class ArrayAdapter implements AdapterInterface
{
    /**
     * @inheritDoc
     */
    public function loadFromFile(string $fileName): array
    {
        if (file_exists($fileName))
        {
            $data = include($fileName);

            if (is_array($data))
            {
                return $data;
            }
        }

        return [];
    }
}