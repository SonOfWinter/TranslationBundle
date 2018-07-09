<?php

/**
 * Translation Service
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Service
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\TranslationBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\Translation;
use SOW\TranslationBundle\Repository\TranslationRepositoryInterface;

/**
 * Class TranslationService
 *
 * @package SOW\TranslationBundle\Service
 */
class TranslationService implements TranslationServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TranslationRepositoryInterface
     */
    protected $repository;

    /**
     * TranslationService constructor.
     *
     * @param EntityManagerInterface $em
     * @param string                 $translationClass
     */
    public function __construct(
        EntityManagerInterface $em,
        string $translationClass
    ) {
        $this->em = $em;
        $this->repository = $em->getRepository($translationClass);
    }

    /**
     * Find all translation for a trnaslatable and a lang
     *
     * @param Translatable $translatable
     * @param string       $lang
     *
     * @throws \UnexpectedValueException
     *
     * @return array
     */
    public function findAllForObjectWithLang(
        Translatable $translatable,
        string $lang
    ) {
        return $this->repository->findBy(
            [
                "entityName" => $translatable->getEntityName(),
                "entityId"   => $translatable->getId(),
                "lang"       => $lang
            ]
        );
    }

    /**
     * Find one translation for a key
     *
     * @param Translatable $translatable
     * @param string       $key
     * @param string       $lang
     *
     * @return Translation|null
     */
    public function findOneForObjectWithLang(
        Translatable $translatable,
        string $key,
        string $lang
    ): ?Translation {
        return $this->repository->findOneBy(
            [
                "entityName" => $translatable->getEntityName(),
                "entityId"   => $translatable->getId(),
                "lang"       => $lang,
                "key"        => $key
            ]
        );
    }

    /**
     * find all translation for translatable
     *
     * @param Translatable $translatable
     *
     * @throws \UnexpectedValueException
     *
     * @return array
     */
    public function findAllForObject(Translatable $translatable)
    {
        return $this->repository->findBy(
            [
                "entityName" => $translatable->getEntityName(),
                "entityId"   => $translatable->getId()
            ],
            ["lang"]
        );
    }

    /**
     * create translation
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param string $key
     * @param string $value
     * @param bool $flush
     *
     * @return Translation
     */
    public function create(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): Translation {
        $translation = new Translation();
        $translation->setEntityId($translatable->getId())
            ->setEntityName($translatable->getEntityName())
            ->setKey($key)
            ->setLang($lang)
            ->setValue($value);
        if ($flush) {
            $this->em->persist($translation);
            $this->em->flush();
        }
        return $translation;
    }

    /**
     * edit translation
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param string $key
     * @param string $value
     * @param bool $flush
     *
     * @return Translation
     */
    public function edit(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): Translation {
        $translation = $this->findOneForObjectWithLang($translatable, $key, $lang);
        if ($translation) {
            $translation->setValue($value);
            if ($flush) {
                $this->em->flush();
            }
            return $translation;
        } else {
            return $this->create($translatable, $lang, $key, $value, $flush);
        }
    }
}
