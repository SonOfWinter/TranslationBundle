<?php

/**
 * TranslationGroup class
 *
 * @package  SOW\TranslationBundle\Entity
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Entity;

/**
 * Class TranslationGroup
 * Group of translatable's translations for one lang
 *
 * @package SOW\TranslationBundle\Entity
 */
class TranslationGroup
{
    /**
     * List of translation
     * Keys are translation's keys
     *
     * @var AbstractTranslation[]
     */
    protected array $translations = [];

    protected ?Translatable $translatable = null;

    protected string $lang;

    /**
     * TranslationGroup constructor.
     *
     * @param Translatable $translatable
     * @param string $lang
     */
    public function __construct(Translatable $translatable, string $lang)
    {
        $this->translatable = $translatable;
        $this->lang = $lang;
    }

    /**
     * Getter for translations
     *
     * @return AbstractTranslation[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * Setter for translations
     *
     * @param AbstractTranslation[] $translations
     *
     * @return self
     */
    public function setTranslations(array $translations): self
    {
        $this->translations = $translations;
        return $this;
    }

    /**
     * Getter for translatable
     *
     * @return Translatable
     */
    public function getTranslatable(): Translatable
    {
        return $this->translatable;
    }

    /**
     * Getter for lang
     *
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * Setter for lang
     *
     * @param string $lang
     *
     * @return self
     */
    public function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Add or replace translation
     *
     * @param AbstractTranslation $translation
     *
     * @return TranslationGroup
     */
    public function addTranslation(AbstractTranslation $translation): self
    {
        $this->translations[$translation->getKey()] = $translation;
        return $this;
    }

    /**
     * Remove all translations for lang if exist
     *
     * @param AbstractTranslation $translation
     *
     * @return TranslationGroup
     */
    public function removeTranslations(AbstractTranslation $translation): self
    {
        if (array_key_exists($translation->getKey(), $this->translations)) {
            unset($this->translations[$translation->getKey()]);
        }
        return $this;
    }

    /**
     * Return translation for key if exist
     *
     * @param string $key
     *
     * @return null|AbstractTranslation
     */
    public function getKey(string $key): ? AbstractTranslation
    {
        return $this->translations[$key] ?? null;
    }
}
