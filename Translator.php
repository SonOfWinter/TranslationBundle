<?php

/**
 * Translator
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle;

use Psr\Log\LoggerInterface;
use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\AbstractTranslation;
use SOW\TranslationBundle\Entity\TranslationGroup;
use SOW\TranslationBundle\Exception\TranslatableConfigurationException;
use SOW\TranslationBundle\Exception\TranslatorConfigurationException;
use SOW\TranslationBundle\Service\TranslationServiceInterface;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class Translator
 *
 * @package SOW\TranslationBundle
 */
class Translator implements TranslatorInterface
{
    /**
     * @var LoggerInterface|null
     */
    protected $logger;

    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @var TranslationCollection|null
     */
    protected $collection;

    /**
     * @var TranslationServiceInterface
     */
    protected $translationService;

    /**
     * @var array
     */
    protected $langs;

    /**
     * Translator constructor.
     *
     * @param TranslationServiceInterface $translationService
     * @param LoaderInterface $loader
     * @param array $langs
     * @param null|LoggerInterface $logger
     */
    public function __construct(
        TranslationServiceInterface $translationService,
        LoaderInterface $loader,
        array $langs,
        ?LoggerInterface $logger = null
    ) {
        $this->translationService = $translationService;
        $this->loader = $loader;
        $this->langs = $langs;
        $this->logger = $logger;
    }

    /**
     * setResource
     *
     * @param $resource
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        $this->loadCollection();
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
        return $this->translationService->checkTranslation($object, $key, $lang);
    }

    /**
     * getTranslationCollection
     * Get TranslationCollection for a resource
     *
     * @throws TranslatorConfigurationException
     * @throws \Exception
     *
     * @return TranslationCollection
     */
    public function getTranslationCollection(): TranslationCollection
    {
        if ($this->resource === null) {
            throw new TranslatorConfigurationException();
        }
        return $this->collection;
    }

    /**
     * loadCollection
     *
     * @throws \Exception
     *
     * @return TranslationCollection|null
     */
    private function loadCollection()
    {
        $this->collection = $this->loader->load($this->resource, 'annotation');
        return $this->collection;
    }

    /**
     * getTranslationGroupForLang
     * Get all translation in TranslationGroup for lang
     *
     * @param Translatable $translatable
     * @param string $lang
     *
     * @return TranslationGroup
     */
    public function getTranslationGroupForLang(
        Translatable $translatable,
        string $lang
    ): TranslationGroup {
        $translationGroup = new TranslationGroup($translatable, $lang);
        $translations = $this->translationService->findAllForObjectWithLang($translatable, $lang);
        foreach ($translations as $translation) {
            $translationGroup->addTranslation($translation);
        }
        return $translationGroup;
    }

    /**
     * setTranslationForLangAndValue
     * Create or edit translation
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param string $key
     * @param string $value
     * @param bool $flush
     *
     * @return AbstractTranslation
     */
    public function setTranslationForLangAndValue(
        Translatable $translatable,
        string $lang,
        string $key,
        string $value,
        bool $flush = false
    ): AbstractTranslation {
        /** @var AbstractTranslation $translation */
        return $this->translationService->edit($translatable, $lang, $key, $value, $flush);
    }

    /**
     * setTranslationForLangAndValues
     * Set all translation from array match with entity's annotations
     *
     * @param Translatable $translatable
     * @param string $lang
     * @param array $values
     * @param bool $flush
     *
     * @throws TranslatorConfigurationException
     *
     * @return TranslationGroup
     */
    public function setTranslationForLangAndValues(
        Translatable $translatable,
        string $lang,
        array $values,
        bool $flush = false
    ): TranslationGroup {
        if ($this->resource !== get_class($translatable)) {
            $this->setResource(get_class($translatable));
        }
        $translationGroup = new TranslationGroup($translatable, $lang);
        $collection = $this->getTranslationCollection();
        foreach ($collection as $t) {
            if (array_key_exists($t->getKey(), $values)) {
                $translationGroup->addTranslation(
                    $this->setTranslationForLangAndValue(
                        $translatable,
                        $lang,
                        $t->getKey(),
                        $values[$t->getKey()],
                        $flush
                    )
                );
            }
        }
        return $translationGroup;
    }

    /**
     * setTranslations
     *
     * @param Translatable $translatable
     * @param array $translations
     * @param bool $flush
     *
     * @throws TranslatorConfigurationException
     *
     * @return array
     *
     * Translation array must be like :
     * [
     *     "fr" => [
     *         "property" => "translation"
     *     ],
     *     "en" => [
     *         "property" => "translation"
     *     ],
     * ]
     */
    public function setTranslations(Translatable $translatable, array $translations, bool $flush = false): array
    {
        $translationGroups = [];
        foreach ($translations as $lang => $translation) {
            if (in_array($lang, $this->langs) and is_array($translation)) {
                $translationGroups[$lang] = $this->setTranslationForLangAndValues(
                    $translatable,
                    $lang,
                    $translation,
                    false
                );
            } elseif ($this->logger) {
                $this->logger->debug(sprintf("%s not in language list", $lang));
            }
        }
        if ($flush) {
            $this->translationService->flush();
        }
        return $translationGroups;
    }

    /**
     * translate
     * Set entity's properties with translations for lang
     *
     * @param Translatable $translatable
     * @param string $lang
     *
     * @throws TranslatableConfigurationException
     * @throws TranslatorConfigurationException
     *
     * @return Translatable
     */
    public function translate(Translatable $translatable, string $lang): Translatable
    {
        if ($this->resource !== get_class($translatable)) {
            $this->setResource(get_class($translatable));
        }
        $translationGroup = $this->getTranslationGroupForLang($translatable, $lang);
        $collection = $this->getTranslationCollection();
        foreach ($collection as $t) {
            $translation = $translationGroup->getKey($t->getKey());
            if ($translation) {
                $setter = $t->getSetter();
                $translatable->$setter($translation->getValue());
            }
        }
        return $translatable;
    }

    /**
     * translateForLangs
     * Set entity's properties with translations for langs
     * Return associative array of translated object
     *
     * @param Translatable $translatable
     * @param array $langs
     *
     * @throws TranslatableConfigurationException
     * @throws TranslatorConfigurationException
     *
     * @return array
     */
    public function translateForLangs(Translatable $translatable, array $langs): array
    {
        $translatables = [];
        foreach ($langs as $lang) {
            if (in_array($lang, $this->langs)) {
                $translatableClone = clone $translatable;
                $translatables[$lang] = $this->translate($translatableClone, $lang);
            } elseif ($this->logger) {
                $this->logger->debug(sprintf("%s not in language list", $lang));
            }
        }
        return $translatables;
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
        return $this->translationService->remove($translation, $flush);
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
        return $this->translationService->removeByObjectKeyAndLang($object, $key, $lang, $flush);
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
        return $this->translationService->removeAllForTranslatable($object, $flush);
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
        return $this->translationService->removeAllByKey($key, $flush);
    }
}
