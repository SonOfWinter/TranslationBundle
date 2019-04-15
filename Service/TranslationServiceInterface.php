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
use SOW\TranslationBundle\Entity\AbstractTranslation;

/**
 * Interface TranslationService
 *
 * @package  SOW\TranslationBundle\Service
 */
interface TranslationServiceInterface
{
    /**
     * flush
     *
     * @return void
     */
    public function flush();

    /**
     * findAllForObjectWithLang
     *
     * @param Translatable $translatable
     * @param string $lang
     *
     * @return mixed
     */
    public function findAllForObjectWithLang(
        Translatable $translatable,
        string $lang
    );

    /**
     * findOneForObjectWithLang
     *
     * @param Translatable $translatable
     * @param string $key
     * @param string $lang
     *
     * @return mixed
     */
    public function findOneForObjectWithLang(
        Translatable $translatable,
        string $key,
        string $lang
    );

    /**
     * findAllForObject
     *
     * @param Translatable $translatable
     *
     * @return mixed
     */
    public function findAllForObject(Translatable $translatable);

    /**
     * findByKey
     *
     * @param string $key
     *
     * @return array
     */
    public function findByKey(string $key): array;

    /**
     * findAllByEntityNameAndLang
     *
     * @param string $entityName
     * @param array $ids
     * @param string $lang
     *
     * @return array
     */
    public function findByEntityNameAndLang(string $entityName, array $ids, string $lang): array;

    /**
     * checkTranslation
     *
     * @param Translatable $object
     * @param string $key
     * @param string $lang
     *
     * @return bool
     */
    public function checkTranslation(Translatable $object, string $key, string $lang): bool;

    /**
     * create
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param string $key
     * @param string $value
     * @param bool $flush
     *
     * @return AbstractTranslation
     */
    public function create(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): AbstractTranslation;

    /**
     * edit
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param string $key
     * @param string $value
     * @param bool $flush
     *
     * @return AbstractTranslation
     */
    public function edit(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): AbstractTranslation;

    /**
     * remove
     *
     * @param AbstractTranslation $translation
     * @param bool $flush
     *
     * @return bool
     */
    public function remove(AbstractTranslation $translation, bool $flush = false): bool;

    /**
     * removeByObjectKeyAndLang
     *
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
     *
     * @param Translatable $object
     * @param bool $flush
     *
     * @return bool
     */
    public function removeAllForTranslatable(Translatable $object, bool $flush = false): bool;

    /**
     * removeAllByKey
     *
     * @param string $key
     * @param bool $flush
     *
     * @return bool
     */
    public function removeAllByKey(string $key, bool $flush = false): bool;
}
