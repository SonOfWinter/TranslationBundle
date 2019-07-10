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
    public function findAllByObjectAndLangs(Translatable $translatable, array $langs);

    /**
     * findAllByEntityNameAndLang
     *
     * @param string $entityName
     * @param array $ids
     * @param string $lang
     *
     * @return mixed
     */
    public function findAllByEntityNameAndLang(string $entityName, array $ids, string $lang);

    /**
     * findBy
     *
     * @param array $data
     * @param array $orderBy
     *
     * @return mixed
     */
    public function findBy(array $data, array $orderBy = []);

    /**
     * findOneBy
     *
     * @param array $data
     *
     * @return mixed
     */
    public function findOneBy(array $data);
}
