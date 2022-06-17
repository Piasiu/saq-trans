<?php
namespace Saq\Trans\Adapter;

use Saq\Trans\AdapterInterface;

class JsonAdapter implements AdapterInterface
{
    /**
     * @inheritDoc
     */
    public function loadFromFile(string $filePath): array
    {
        if (file_exists($filePath))
        {
            $data = json_decode($filePath, true);

            if (is_array($data))
            {
                return $data;
            }
        }

        return [];
    }
}