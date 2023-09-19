<?php

/**
 * Translation Collection class
 *
 * @package  SOW\TranslationBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Symfony\Component\Config\Resource\ResourceInterface;
use Traversable;

/**
 * Class TranslationCollection
 *
 * @package SOW\TranslationBundle
 */
class TranslationCollection implements IteratorAggregate, Countable
{
    /**
     * @var Translation[]
     */
    private array $translations = [];

    private array $resources = [];

    /**
     * @return ArrayIterator|Traversable
     */
    public function getIterator(): Traversable | ArrayIterator
    {
        return new ArrayIterator($this->translations);
    }

    /**
     * Return number of translation element
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->translations);
    }

    /**
     * Add a translation element
     *
     * @param Translation $translation
     *
     * @return void
     */
    public function add(Translation $translation): void
    {
        unset($this->translations[$translation->getKey()]);
        $this->translations[$translation->getKey()] = $translation;
    }

    /**
     * Get all translation elements
     *
     * @return Translation[]
     */
    public function all(): array
    {
        return $this->translations;
    }

    /**
     * Get a translation element by key
     *
     * @param $key
     *
     * @return null|Translation
     */
    public function get($key): ?Translation
    {
        return $this->translations[$key] ?? null;
    }

    /**
     * Remove a translation element by key
     *
     * @param string|string[] $key The translation key or an array of translation keys
     *
     * @return void
     */
    public function remove(string|array $key): void
    {
        if (!empty($key)) {
            foreach ((array)$key as $k) {
                unset($this->translations[$k]);
            }
        }
    }

    /**
     * Get all keys
     *
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->translations);
    }

    /**
     * Merge the collection with a new collection
     *
     * @param TranslationCollection $collection
     *
     * @return void
     */
    public function addCollection(TranslationCollection $collection): void
    {
        foreach ($collection->all() as $key => $translation) {
            unset($this->translations[$key]);
            $this->translations[$key] = $translation;
        }
        foreach ($collection->getResources() as $resource) {
            $this->addResource($resource);
        }
    }


    /**
     * Returns an array of resources loaded to build this collection.
     *
     * @return array
     */
    public function getResources(): array
    {
        return array_values($this->resources);
    }

    /**
     * Adds a resource for this collection. If the resource already exists
     * it is not added.
     *
     * @param ResourceInterface $resource
     *
     * @return void
     */
    public function addResource(ResourceInterface $resource): void
    {
        $key = (string) $resource;
        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }
}
