<?php
namespace Saq\Trans\Language;

use Saq\Trans\LanguageInterface;

class Fr implements LanguageInterface
{
    /**
     * @inheritDoc
     */
    public function getCode(): string
    {
        return 'fr';
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
        return $value > 1 ? 1 : 0;
    }
}