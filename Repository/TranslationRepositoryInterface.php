<?php

/**
 * Translation Repository Interface
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Repository
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

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
