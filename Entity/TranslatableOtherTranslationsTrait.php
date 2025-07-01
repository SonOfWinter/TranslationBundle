<?php

namespace SOW\TranslationBundle\Entity;

/**
 * Class TranslatableOtherTranslationsTrait
 *
 * @package SOW\TranslationBundle\Entity
 */
trait TranslatableOtherTranslationsTrait
{
    /**
     * @var array<string, string>
     */
    protected array $otherTranslations = [];

    /**
     * getOtherTranslations
     * return list of translation how not match with collection
     *
     * @return array<string, string>
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
