<?php
namespace Saq\Trans;

use JetBrains\PhpStorm\Pure;
use Saq\Trans\PluralForm\En;

class Translator
{
    /**
     * @var string
     */
    private string $path;

    /**
     * @var AdapterInterface
     */
    private AdapterInterface $adapter;

    /**
     * @var string
     */
    private string $defaultLanguageCode;

    /**
     * @var LanguageInterface[]
     */
    private array $languages = [];

    /**
     * @var array
     */
    private array $data = [];

    public function __construct(string $path, AdapterInterface $adapter, ?LanguageInterface $defaultLanguage = null)
    {
        $this->path = $path;
        $this->adapter = $adapter;
        $this->addLanguage($defaultLanguage ?? new En());
    }

    public function translate(string $fileName, string $text, array $params, ?string $languageCode = null): string
    {
        $language = $this->getLanguage($languageCode);
        $data = $this->getData($language, $fileName);

        if (isset($text, $data) && is_string($data[$text]))
        {
            $text = $data[$text];
        }

        return $this->fillParams($text, $params);
    }

    public function pluralTranslate(string $fileName, string $text, int $value, array $params, ?string $languageCode = null): string
    {
        $language = $this->getLanguage($languageCode);
        $data = $this->getData($language, $fileName);

        if (isset($text, $data) && is_array($data[$text]) && count($data[$text]) === $language->getNumberOfPluralForms())
        {
            $pluralForm = $language->getPluralForm($value);

            if (is_string($this->data[$text][$pluralForm]))
            {
                $text = $this->data[$text][$pluralForm];
            }
        }

        return $this->fillParams($text, $params);
    }

    public function getDefaultLanguageCode(): string
    {
        return $this->defaultLanguageCode;
    }

    public function setDefaultLanguageCode(string $code): void
    {
        if (isset($this->languages[$code]))
        {
            $this->defaultLanguageCode = $code;
        }
    }

    #[Pure]
    public function getLanguage(?string $code): LanguageInterface
    {
        if ($code !== null && isset($this->languages[$code]))
        {
            return $this->languages[$code];
        }

        return $this->languages[$this->getDefaultLanguageCode()];
    }

    public function addLanguage(LanguageInterface $language, bool $asDefault = false): void
    {
        $this->languages[$language->getCode()] = $language;

        if (!array_key_exists($language->getCode(), $this->data))
        {
            $this->data[$language->getCode()] = [];
        }

        if ($asDefault)
        {
            $this->setDefaultLanguageCode($language->getCode());
        }
    }

    private function getData(LanguageInterface $language, string $fileName): array
    {
        if (!array_key_exists($fileName, $this->data[$language->getCode()]))
        {
            $filePath = $this->path.DIRECTORY_SEPARATOR.$language->getCode().DIRECTORY_SEPARATOR.$fileName;
            $this->data[$language->getCode()][$fileName] = $this->adapter->loadFromFile($filePath);
        }

        return $this->data[$language->getCode()][$fileName];
    }

    private function fillParams(string $text, array $params): string
    {
        foreach ($params as $name => $value)
        {
            $text = str_replace('__'.$name.'__', $value, $text);
        }

        return $text;
    }
}