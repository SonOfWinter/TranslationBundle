<?php

/**
 * Translation service interface
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Service
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
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
