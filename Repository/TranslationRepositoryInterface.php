<?php

namespace SOW\TranslationBundle\Repository;

use SOW\TranslationBundle\Entity\AbstractTranslation;
use SOW\TranslationBundle\Entity\Translatable;

/**
 * Interface TranslationRepositoryInterface
 *
 * @package  SOW\TranslationBundle\Repository
 * @method findBy(array $data, array $orderBy = []): array
 * @method findOneBy(array $data): mixed
 */
interface TranslationRepositoryInterface
{
    /**
     * find all by object and langs
     *
     * @param Translatable $translatable
     * @param array $langs
     *
     * @return AbstractTranslation[]
     */
    public function findAllByObjectAndLangs(Translatable $translatable, array $langs): array;

    /**
     * findAllByEntityNameAndLang
     *
     * @param string $entityName
     * @param array $ids
     * @param string $lang
     *
     * @return AbstractTranslation[]
     */
    public function findAllByEntityNameAndLang(string $entityName, array $ids, string $lang): array;
}
