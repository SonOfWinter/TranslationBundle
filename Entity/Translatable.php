<?php

namespace SOW\TranslationBundle\Entity;

/**
 * Interface Translatable
 *
 * @package  SOW\TranslationBundle\Entity
 */
interface Translatable
{
    /**
     * getEntityName
     * Getter for entityName
     *
     * @return string
     */
    public function getEntityName(): string;

    /**
     * getId
     * Getter for id
     *
     * @return string
     */
    public function getId(): string;

    /**
     * getOtherTranslations
     * return list of translation how not match with collection
     *
     * @return array
     */
    public function getOtherTranslations(): array;

    /**
     * setOtherTranslation
     * set a translation how not match with collection
     *
     * @param string $key
     * @param string $value
     *
     * @return Translatable
     */
    public function setOtherTranslation(string $key, string $value): Translatable;
}
