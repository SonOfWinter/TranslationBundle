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

/**
 * Interface TranslatorInterface
 *
 * @package  SOW\TranslationBundle
 */
interface TranslatorInterface
{
    public function setResource($resource);

    public function getTranslationCollection(): TranslationCollection;

    public function getTranslationGroupForLang(
        Translatable $translatable,
        string $lang
    ): TranslationGroup;

    public function setTranslationForLangAndValue(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush
    ): AbstractTranslation;

    public function setTranslationForLangAndValues(
        Translatable $translatable,
        string $lang,
        array $values,
        bool $flush
    ): TranslationGroup;

    public function translate(Translatable $translatable, string $lang): Translatable;

    public function remove(AbstractTranslation $translation, bool $flush = false): bool;

    public function removeByObjectKeyAndLang(
        Translatable $object,
        string $key,
        string $lang,
        bool $flush = false
    ): bool;

    public function removeAllForTranslatable(Translatable $object, bool $flush = false): bool;

    public function removeAllByKey(string $key, bool $flush = false): bool;
}
