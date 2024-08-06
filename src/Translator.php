<?php
namespace Saq\Trans;

use JetBrains\PhpStorm\Pure;
use Saq\Trans\Language\En;

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
     * @var string
     */
    private string $delimiter;

    /**
     * @var array
     */
    private array $data = [];

    /**
     * @param string $path
     * @param AdapterInterface $adapter
     * @param LanguageInterface|null $defaultLanguage
     * @param string $delimiter
     */
    public function __construct(string $path, AdapterInterface $adapter, ?LanguageInterface $defaultLanguage = null, string $delimiter = '__')
    {
        $this->path = $path;
        $this->adapter = $adapter;
        $this->addLanguage($defaultLanguage ?? new En(), true);
        $this->delimiter = $delimiter;
    }

    /**
     * @param string $fileSubPath
     * @param string $text
     * @param array $parameters
     * @param string|null $languageCode
     * @return string
     */
    public function translate(string $fileSubPath, string $text, array $parameters = [], ?string $languageCode = null): string
    {
        $language = $this->getLanguage($languageCode);
        $data = $this->getData($language, $fileSubPath);

        if (isset($data[$text]) && is_string($data[$text]))
        {
            $text = $data[$text];
        }

        return $this->includeParameters($text, $parameters);
    }

    /**
     * @param string $fileSubPath
     * @param string $text
     * @param int $value
     * @param array $parameters
     * @param string|null $languageCode
     * @return string
     */
    public function pluralTranslate(string $fileSubPath, string $text, int $value, array $parameters = [], ?string $languageCode = null): string
    {
        $language = $this->getLanguage($languageCode);
        $data = $this->getData($language, $fileSubPath);

        if (isset($data[$text]) && is_array($data[$text]) && count($data[$text]) === $language->getNumberOfPluralForms())
        {
            $pluralForm = $language->getPluralForm($value);

            if (is_string($data[$text][$pluralForm]))
            {
                $text = $data[$text][$pluralForm];
            }
        }

        $parameters['value'] = $value;
        return $this->includeParameters($text, $parameters);
    }

    /**
     * @return string
     */
    public function getDefaultLanguageCode(): string
    {
        return $this->defaultLanguageCode;
    }

    /**
     * @param string $languageCode
     */
    public function setDefaultLanguageCode(string $languageCode): void
    {
        if (isset($this->languages[$languageCode]))
        {
            $this->defaultLanguageCode = $languageCode;
        }
    }

    /**
     * @param string|null $languageCode
     * @return LanguageInterface
     */
    #[Pure]
    public function getLanguage(?string $languageCode): LanguageInterface
    {
        if ($languageCode !== null && isset($this->languages[$languageCode]))
        {
            return $this->languages[$languageCode];
        }

        return $this->languages[$this->getDefaultLanguageCode()];
    }

    /**
     * @param LanguageInterface $language
     * @param bool $asDefault
     */
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

    /**
     * @param LanguageInterface $language
     * @param string $fileSubPath
     * @return array
     */
    private function getData(LanguageInterface $language, string $fileSubPath): array
    {
        if (!array_key_exists($fileSubPath, $this->data[$language->getCode()]))
        {
            $filePath = $this->path.DIRECTORY_SEPARATOR.$language->getCode().DIRECTORY_SEPARATOR.$fileSubPath;
            $this->data[$language->getCode()][$fileSubPath] = $this->adapter->loadFromFile($filePath);
        }

        return $this->data[$language->getCode()][$fileSubPath];
    }

    /**
     * @param string $text
     * @param array $parameters
     * @return string
     */
    private function includeParameters(string $text, array $parameters): string
    {
        foreach ($parameters as $name => $value)
        {
            $text = str_replace('__'.$name.'__', $value, $text);
        }

        return $text;
    }
}
