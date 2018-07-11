<?php

/**
 * Translation service interface
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Service
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Service;

use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\Translation;

/**
 * Interface TranslationService
 *
 * @package  SOW\TranslationBundle\Service
 */
interface TranslationServiceInterface
{
    public function findAllForObjectWithLang(
        Translatable $translatable,
        string $lang
    );

    public function findOneForObjectWithLang(
        Translatable $translatable,
        string $key,
        string $lang
    );

    public function findAllForObject(Translatable $translatable);

    public function create(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): Translation;

    public function edit(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): Translation;
}
