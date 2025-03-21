<?php
namespace Saq\Trans\Language;

use Saq\Trans\LanguageInterface;

class En implements LanguageInterface
{
    /**
     * @inheritDoc
     */
    public function getCode(): string
    {
        return 'en';
    }

    /**
     * @inheritDoc
     */
    public function getNumberOfPluralForms(): int
    {
        return 2;
    }

    /**
     * @inheritDoc
     */
    public function getPluralForm(int $value): int
    {
        return $value !== 1 ? 1 : 0;
    }
}