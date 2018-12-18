<?php

/**
 * Translation Repository Interface
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Annotation
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
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
    /**
     * find all by object and langs
     *
     * @param Translatable $translatable
     * @param array $langs
     *
     * @return mixed
     */
    public function findAllByObjectAndLangs(
        Translatable $translatable,
        array $langs
    );

    public function findBy(array $data, array $orderBy = []);

    public function findOneBy(array $data);
}
