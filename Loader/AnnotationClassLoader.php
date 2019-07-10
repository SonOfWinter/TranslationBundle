<?php

/**
 * Annotation class loader
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Loader;

use SOW\TranslationBundle\Exception\TranslatableConfigurationException;
use SOW\TranslationBundle\Translation;
use SOW\TranslationBundle\TranslationCollection;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Config\Resource\FileResource;

/**
 * Class AnnotationClassLoader
 *
 * @package SOW\TranslationBundle\Loader
 */
class AnnotationClassLoader implements LoaderInterface
{
    /**
     * Reader for annotation
     *
     * @var Reader
     */
    protected $reader;

    /**
     * Annotation class name
     *
     * @var string
     */
    protected $translationAnnotationClass;

    /**
     * AnnotationClassLoader constructor.
     *
     * @param Reader $reader
     * @param        $translationAnnotationClass
     */
    public function __construct(Reader $reader, $translationAnnotationClass)
    {
        $this->reader = $reader;
        $this->translationAnnotationClass = $translationAnnotationClass;
    }

    /**
     * Sets the annotation class to read translation properties from.
     *
     * @param $class
     *
     * @return void
     */
    public function setTranslationAnnotationClass($class)
    {
        $this->translationAnnotationClass = $class;
    }

    /**
     * Load annotations from class
     *
     * @param mixed $class
     * @param null $type
     *
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     * @throws TranslatableConfigurationException
     *
     * @return TranslationCollection
     */
    public function load($class, $type = null): TranslationCollection
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }
        $class = new \ReflectionClass($class);
        if ($class->isAbstract()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Annotations from class "%s" cannot be read as it is abstract.',
                    $class->getName()
                )
            );
        }
        $collection = new TranslationCollection();
        $collection->addResource(new FileResource($class->getFileName()));
        $methods = [];
        foreach ($class->getMethods() as $reflectionMethod) {
            $methods[] = $reflectionMethod->getName();
        }
        foreach ($class->getProperties() as $property) {
            foreach ($this->reader->getPropertyAnnotations($property) as $annot) {
                if ($annot instanceof $this->translationAnnotationClass) {
                    $this->addTranslation(
                        $collection,
                        $annot,
                        $methods,
                        $property
                    );
                }
            }
        }
        return $collection;
    }

    /**
     * Add translation class to TranslationCollection
     *
     * @param TranslationCollection $collection
     * @param \SOW\TranslationBundle\Annotation\Translation $annot
     * @param array $methods
     * @param \ReflectionProperty $property
     *
     * @throws TranslatableConfigurationException
     *
     * @return void
     */
    protected function addTranslation(
        TranslationCollection $collection,
        \SOW\TranslationBundle\Annotation\Translation $annot,
        array $methods,
        \ReflectionProperty $property
    ) {
        $propertyName = $property->getName();
        $method = $annot->getSetter() ?? 'set' . ucfirst($propertyName);
        if (in_array($method, $methods)) {
            $translation = new Translation($annot->getKey() ?? $propertyName, $method);
            $collection->add($translation);
        } else {
            throw new TranslatableConfigurationException();
        }
    }

    /**
     * Check if resource is supported
     *
     * @param mixed $resource
     * @param null $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource)
            && preg_match('/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/', $resource)
            && (!$type || 'annotation' === $type);
    }

    /**
     * Not implemented
     *
     * @return LoaderResolverInterface|void
     */
    public function getResolver()
    {
    }

    /**
     * Not implemented
     *
     * @param LoaderResolverInterface $resolver
     *
     * @return void
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}
