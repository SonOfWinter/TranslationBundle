<?php

namespace SOW\TranslationBundle\Loader;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;
use SOW\TranslationBundle\Exception\TranslatableConfigurationException;
use SOW\TranslationBundle\Translation;
use SOW\TranslationBundle\TranslationCollection;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Config\Resource\FileResource;

/**
 * Class AttributeClassLoader
 *
 * @package SOW\TranslationBundle\Loader
 */
class AttributeClassLoader implements LoaderInterface
{
    protected string $translationAttributeClass;

    private LoaderResolverInterface $resolver;

    /**
     * AttributeClassLoader constructor.
     *
     * @param $translationAttributeClass
     */
    public function __construct($translationAttributeClass)
    {
        $this->translationAttributeClass = $translationAttributeClass;
    }

    /**
     * Sets the attribute class to read translation properties from.
     *
     * @param string $class
     *
     * @return void
     */
    public function setTranslationAttributeClass(string $class): void
    {
        $this->translationAttributeClass = $class;
    }

    /**
     * Load attributes from class
     *
     * @param mixed $resource
     * @param null $type
     *
     * @throws TranslatableConfigurationException
     * @throws InvalidArgumentException
     * @return TranslationCollection
     */
    public function load(mixed $resource, $type = null): TranslationCollection
    {
        if (!class_exists($resource)) {
            throw new InvalidArgumentException(sprintf('Class "%s" does not exist.', $resource));
        }
        $resource = new ReflectionClass($resource);
        if ($resource->isAbstract()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Attributes from class "%s" cannot be read as it is abstract.',
                    $resource->getName()
                )
            );
        }
        $collection = new TranslationCollection();
        $collection->addResource(new FileResource($resource->getFileName()));
        $methods = [];
        foreach ($resource->getMethods() as $reflectionMethod) {
            $methods[] = $reflectionMethod->getName();
        }
        foreach ($resource->getProperties() as $property) {
            $attributes = $property->getAttributes($this->translationAttributeClass);
            foreach ($attributes as $attribute) {
                $listener = $attribute->newInstance();
                if (get_class($listener) === $this->translationAttributeClass) {
                    $this->addTranslation(
                        $collection,
                        $listener,
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
     * @param \SOW\TranslationBundle\Attribute\Translation $attribute
     * @param array $methods
     * @param ReflectionProperty $property
     *
     * @throws TranslatableConfigurationException
     * @return void
     */
    protected function addTranslation(
        TranslationCollection $collection,
        \SOW\TranslationBundle\Attribute\Translation $attribute,
        array $methods,
        ReflectionProperty $property
    ): void {
        $propertyName = $property->getName();
        $method = $attribute->getSetter() ?? 'set' . ucfirst($propertyName);
        if (in_array($method, $methods)) {
            $translation = new Translation($attribute->getKey() ?? $propertyName, $method);
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
    public function supports(mixed $resource, $type = null): bool
    {
        return is_string($resource)
            && preg_match('/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/', $resource)
            && (!$type || 'attribute' === $type);
    }

    public function getResolver(): LoaderResolverInterface
    {
        return $this->resolver;
    }

    public function setResolver(LoaderResolverInterface $resolver): void
    {
        $this->resolver = $resolver;
    }
}
