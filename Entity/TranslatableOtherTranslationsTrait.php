<?php

/**
 * @package  SOW\TranslationBundle\Entity
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\TranslationBundle\Entity;

/**
 * Class TranslatableOtherTranslationsTrait
 *
 * @package SOW\TranslationBundle\Entity
 */
trait TranslatableOtherTranslationsTrait
{
    protected $otherTranslations = [];

    /**
     * getOtherTranslations
     * return list of translation how not match with collection
     *
     * @return array
     */
    public function getOtherTranslations(): array
    {
        return $this->otherTranslations;
    }

    /**
     * setOtherTranslation
     * set a translation how not match with collection
     *
     * @param string $key
     * @param string $value
     *
     * @return Translatable
     */
    public function setOtherTranslation(string $key, string $value): Translatable
    {
        $this->otherTranslations[$key] = $value;
        return $this;
    }
}
