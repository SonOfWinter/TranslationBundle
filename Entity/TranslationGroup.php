<?php

/**
 * TranslationGroup class
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TranslationGroup
 * Group of translatable's translations for one lang
 *
 * @package SOW\TranslationBundle\Entity
 *
 * @ORM\Table(name="`translation`")
 * @ORM\Entity(repositoryClass="SOW\TranslationBundle\Repository\TranslationRepository")
 */
class TranslationGroup
{
    /**
     * List of translation
     * Keys are translation's keys
     *
     * @var Translation[]
     */
    protected $translations = [];

    /**
     * @var Translatable
     */
    protected $translatable = null;

    /**
     * @var string
     */
    protected $lang;

    /**
     * TranslationGroup constructor.
     *
     * @param Translatable $translatable
     * @param string $lang
     *
     */
    public function __construct(Translatable $translatable, string $lang)
    {
        $this->translatable = $translatable;
        $this->lang = $lang;
    }

    /**
     * Getter for translations
     *
     * @return Translation[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * Setter for translations
     *
     * @param Translation[] $translations
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
     * @param Translation $translation
     *
     * @return TranslationGroup
     */
    public function addTranslation(Translation $translation): self
    {
        $this->translations[$translation->getKey()] = $translation;
        return $this;
    }

    /**
     * Remove all translations for lang if exist
     *
     * @param string $lang
     *
     * @return TranslationGroup
     */
    public function removeTranslations(Translation $translation): self
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
     * @return null|Translation
     */
    public function getKey(string $key): ?Translation
    {
        return $this->translations[$key] ?? null;
    }
}
