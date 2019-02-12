<?php

/**
 * Translation Service
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Service
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\AbstractTranslation;
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
     * @var string
     */
    protected $translationClassName;

    /**
     * TranslationService constructor.
     *
     * @param EntityManagerInterface $em
     * @param TranslationRepositoryInterface $repository
     * @param string $translationClassName
     */
    public function __construct(
        EntityManagerInterface $em,
        TranslationRepositoryInterface $repository,
        string $translationClassName
    ) {
        $this->em = $em;
        $this->repository = $repository;
        $this->translationClassName = $translationClassName;
    }

    /**
     * flush
     *
     * @return void
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * Find all translation for a translatable and a lang
     *
     * @param Translatable $translatable
     * @param string $lang
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
                "entityId" => $translatable->getId(),
                "lang" => $lang
            ]
        );
    }

    /**
     * Find one translation for a key
     *
     * @param Translatable $translatable
     * @param string $key
     * @param string $lang
     *
     * @return AbstractTranslation|null
     */
    public function findOneForObjectWithLang(
        Translatable $translatable,
        string $key,
        string $lang
    ): ?AbstractTranslation {
        return $this->repository->findOneBy(
            [
                "entityName" => $translatable->getEntityName(),
                "entityId" => $translatable->getId(),
                "lang" => $lang,
                "key" => $key
            ]
        );
    }

    /**
     * find all translation for translatable
     *
     * @param Translatable $translatable
     *
     * @return array
     */
    public function findAllForObject(Translatable $translatable)
    {
        return $this->repository->findBy(
            [
                "entityName" => $translatable->getEntityName(),
                "entityId" => $translatable->getId()
            ],
            ["lang" => "ASC"]
        );
    }

    /**
     * findByKey
     *
     * @param string $key
     *
     * @return AbstractTranslation[]|array
     */
    public function findByKey(string $key): array
    {
        $translations = $this->repository->findBy(
            [
                'key' => $key
            ],
            ["lang" => "ASC"]
        );
        return $translations;
    }

    /**
     * checkTranslation
     *
     * @param Translatable $object
     * @param string $key
     * @param string $lang
     *
     * @return bool
     */
    public function checkTranslation(Translatable $object, string $key, string $lang): bool
    {
        $translation = $this->findOneForObjectWithLang($object, $key, $lang);
        return $translation !== null;
    }

    /**
     * create translation
     * Auto persist new translation
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param string $key
     * @param string $value
     * @param bool $flush
     *
     * @return AbstractTranslation
     */
    public function create(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): AbstractTranslation {
        $class = $this->translationClassName;
        $translation = new $class();
        $translation->setEntityId($translatable->getId())
            ->setEntityName($translatable->getEntityName())
            ->setKey($key)
            ->setLang($lang)
            ->setValue($value);
        $this->em->persist($translation);
        if ($flush) {
            $this->flush();
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
     * @return AbstractTranslation
     */
    public function edit(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): AbstractTranslation {
        $translation = $this->findOneForObjectWithLang($translatable, $key, $lang);
        if ($translation) {
            $translation->setValue($value);
            if ($flush) {
                $this->flush();
            }
            return $translation;
        } else {
            return $this->create($translatable, $lang, $key, $value, $flush);
        }
    }

    /**
     * remove
     *
     * @param AbstractTranslation $translation
     * @param bool $flush
     *
     * @return bool
     */
    public function remove(AbstractTranslation $translation, bool $flush = false): bool
    {
        $this->em->remove($translation);
        if ($flush) {
            $this->flush();
            return true;
        }
        return true;
    }

    /**
     * removeByObjectKeyAndLang
     *
     * @param Translatable $object
     * @param string $key
     * @param string $lang
     * @param bool $flush
     *
     * @return bool
     */
    public function removeByObjectKeyAndLang(
        Translatable $object,
        string $key,
        string $lang,
        bool $flush = false
    ): bool {
        $translation = $this->findOneForObjectWithLang($object, $key, $lang);
        if ($translation !== null) {
            return $this->remove($translation, $flush);
        } else {
            return true;
        }
    }

    /**
     * removeAllForTranslatable
     *
     * @param Translatable $object
     * @param bool $flush
     *
     * @return bool
     */
    public function removeAllForTranslatable(Translatable $object, bool $flush = false): bool
    {
        $translations = $this->findAllForObject($object);
        foreach ($translations as $translation) {
            $this->remove($translation);
        }
        if ($flush) {
            $this->flush();
        }
        return true;
    }

    /**
     * removeAllByKey
     *
     * @param string $key
     * @param bool $flush
     *
     * @return bool
     */
    public function removeAllByKey(string $key, bool $flush = false): bool
    {
        $translations = $this->findByKey($key);
        foreach ($translations as $translation) {
            $this->remove($translation);
        }
        if ($flush) {
            $this->flush();
        }
        return true;
    }
}
