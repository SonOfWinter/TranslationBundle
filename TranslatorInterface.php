<?php

/**
 * Translator Interface
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle;

use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\TranslationGroup;
use SOW\TranslationBundle\Entity\AbstractTranslation;
use SOW\TranslationBundle\Exception\TranslatableConfigurationException;
use SOW\TranslationBundle\Exception\TranslatorConfigurationException;

/**
 * Interface TranslatorInterface
 *
 * @package  SOW\TranslationBundle
 */
interface TranslatorInterface
{
    /**
     * setResource
     *
     * @param $resource
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setResource($resource);

    /**
     * checkTranslation
     * Check if a translation matches key-lang association
     *
     * @param Translatable $object
     * @param string $key
     * @param string $lang
     *
     * @return bool
     */
    public function checkTranslation(Translatable $object, string $key, string $lang): bool;

    /**
     * getTranslationCollection
     * Get TranslationCollection for a resource
     *
     * @throws TranslatorConfigurationException
     * @throws \Exception
     *
     * @return TranslationCollection
     */
    public function getTranslationCollection(): TranslationCollection;

    /**
     * getTranslationGroupForLang
     * Get all translation in TranslationGroup for lang
     *
     * @param Translatable $translatable
     * @param string $lang
     *
     * @return TranslationGroup
     */
    public function getTranslationGroupForLang(
        Translatable $translatable,
        string $lang
    ): TranslationGroup;

    /**
     * setTranslationForLangAndValue
     * Create or edit translation
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param string $key
     * @param string $value
     * @param bool $flush
     *
     * @return AbstractTranslation
     */
    public function setTranslationForLangAndValue(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush
    ): AbstractTranslation;

    /**
     * setTranslationForLangAndValues
     * Set all translation from array match with entity's annotations
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param array $values
     * @param bool $flush
     *
     * @throws TranslatorConfigurationException
     *
     * @return TranslationGroup
     */
    public function setTranslationForLangAndValues(
        Translatable $translatable,
        string $lang,
        array $values,
        bool $flush = false
    ): TranslationGroup;

    /**
     * translate
     * Set entity's properties with translations for lang
     *
     * @param Translatable $translatable
     * @param string $lang
     *
     * @throws TranslatableConfigurationException
     * @throws TranslatorConfigurationException
     *
     * @return Translatable
     */
    public function translate(Translatable $translatable, string $lang): Translatable;

    /**
     * remove
     * delete a translation
     *
     * @param AbstractTranslation $translation
     * @param bool $flush
     *
     * @return bool
     */
    public function remove(AbstractTranslation $translation, bool $flush = false): bool;

    /**
     * removeByObjectKeyAndLang
     * search and delete a translation matches object-key-lang association
     * @param Translatable $object
     * @param string $key
     * @param string $lang
     * @param bool $flush
     *
     * @return bool
     */
    public function removeByObjectKeyAndLang(
        Translatable $object,
        string $key,
        string $lang,
        bool $flush = false
    ): bool;

    /**
     * removeAllForTranslatable
     * delete all translation for an object
     *
     * @param Translatable $object
     * @param bool $flush
     *
     * @return bool
     */
    public function removeAllForTranslatable(Translatable $object, bool $flush = false): bool;

    /**
     * removeAllByKey
     * delete all translation for a key
     *
     * @param string $key
     * @param bool $flush
     *
     * @return bool
     */
    public function removeAllByKey(string $key, bool $flush = false): bool;
}
