<?php

/**
 * Translatable Interface
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Entity
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

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
}
