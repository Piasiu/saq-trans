<?php
namespace Saq\Trans\PluralForm;

use Saq\Trans\LanguageInterface;

class Pl implements LanguageInterface
{
    /**
     * @inheritDoc
     */
    public function getCode(): string
    {
        return 'pl';
    }

    /**
     * @inheritDoc
     */
    public function getNumberOfPluralForms(): int
    {
        return 3;
    }

    /**
     * @inheritDoc
     */
    public function getPluralForm(int $value): int
    {
        return $value === 1 ? 0 : ($value % 10 >= 2 && $value % 10 <=4 && ($value %100 <12 || $value % 100 >14) ? 1 : 2);
    }
}