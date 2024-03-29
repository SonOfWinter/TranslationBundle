<?php

/**
 * Translator
 *
 * @package  SOW\TranslationBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle;

use Exception;
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
    public const METHOD_ANNOTATION = "annotation";
    public const METHOD_ATTRIBUTE = "attribute";

    protected ?string $resource = null;

    protected LoaderInterface $loader;

    protected TranslationServiceInterface $translationService;

    protected array $langs;

    protected ?LoggerInterface $logger = null;

    protected ?TranslationCollection $collection = null;

    /**
     * Translator constructor.
     *
     * @param TranslationServiceInterface $translationService
     * @param LoaderInterface $annotationLoader
     * @param LoaderInterface $attributeLoader
     * @param array $langs
     * @param string $translationMethod
     * @param null|LoggerInterface $logger
     *
     * @throws TranslatorConfigurationException
     */
    public function __construct(
        TranslationServiceInterface $translationService,
        LoaderInterface $annotationLoader,
        LoaderInterface $attributeLoader,
        array $langs,
        string $translationMethod,
        ?LoggerInterface $logger = null
    ) {
        $this->translationService = $translationService;
        if ($translationMethod === self::METHOD_ATTRIBUTE) {
            $this->loader = $attributeLoader;
        } elseif ($translationMethod === self::METHOD_ANNOTATION) {
            $this->loader = $annotationLoader;
        } else {
            throw new TranslatorConfigurationException("Wrong translator method");
        }
        $this->langs = $langs;
        $this->logger = $logger;
    }

    /**
     * setResource
     *
     * @param $resource
     *
     * @throws Exception
     *
     * @return void
     */
    public function setResource($resource)
    {
        if (is_string($resource)) {
            $this->resource = $resource;
        } else {
            $this->resource = get_class($resource);
        }
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
     * @throws Exception
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
     * @throws Exception
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
                        $values[$t->getKey()] ?? '',
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
        foreach ($translationGroup->getTranslations() as $translation) {
            $key = $translation->getKey();
            $t = $collection->get($key);
            if ($t !== null) {
                $setter = $t->getSetter();
                $translatable->$setter($translation->getValue());
            } else {
                $translatable->setOtherTranslation($key, $translation->getValue());
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
     * translateAll
     *
     * @param string $entityName
     * @param array $translatables
     * @param string $lang
     *
     * @throws TranslatorConfigurationException
     *
     * @return array
     */
    public function translateAll(string $entityName, array $translatables, string $lang): array
    {
        if (empty($translatables)) {
            return [];
        }
        $elem = $translatables[0];
        $this->setResource($elem);
        $collection = $this->getTranslationCollection();
        $map = $this->translatableArrayToMap($translatables);
        $translations = $this->translationService->findByEntityNameAndLang($entityName, array_keys($map), $lang);
        /** @var \SOW\TranslationBundle\Entity\Translation $translation */
        foreach ($translations as $translation) {
            $id = $translation->getEntityId();
            if (array_key_exists($id, $map)) {
                $key = $translation->getKey();
                $t = $collection->get($key);
                if ($t !== null) {
                    $setter = $collection->get($key)->getSetter();
                    $map[$id]->$setter($translation->getValue());
                } else {
                    $map[$id]->setOtherTranslation($key, $translation->getValue());
                }
            }
        }
        return $map;
    }

    /**
     * translatableArrayToMap
     *
     * @param array $translatables
     *
     * @return array
     */
    private function translatableArrayToMap(array $translatables): array
    {
        $map = [];
        foreach ($translatables as $translatable) {
            if ($translatable instanceof Translatable) {
                $map[$translatable->getId()] = $translatable;
            }
        }
        return $map;
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
