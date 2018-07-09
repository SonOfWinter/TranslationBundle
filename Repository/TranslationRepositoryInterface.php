<?php

namespace SOW\TranslationBundle\Repository;

use SOW\TranslationBundle\Entity\Translatable;

/**
 * Interface TranslationRepositoryInterface
 *
 * @package  SOW\TranslationBundle\Repository
 */
interface TranslationRepositoryInterface
{
    public function findAllByObjectAndLangs(
        Translatable $translatable,
        array $langs
    );
}
