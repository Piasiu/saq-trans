<?php
namespace Saq\Trans;

interface LanguageInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return int
     */
    public function getNumberOfPluralForms(): int;

    /**
     * @param int $value
     * @return int
     */
    public function getPluralForm(int $value): int;
}