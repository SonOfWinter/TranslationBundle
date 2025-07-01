<?php

namespace SOW\TranslationBundle\Attribute;

use Attribute;

/**
 * Class Translation
 *
 * @package SOW\TranslationBundle\Attribute
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Translation
{
    public string $key;

    public string $setter;

    /**
     * Translation constructor.
     *
     * @param string $key
     * @param string|null $setter
     */
    public function __construct(string $key, ?string $setter = null)
    {
        $this->key = $key;
        if ($setter) {
            $this->setter = $setter;
        } else {
            $this->setter = 'set' . ucwords($key);
        }
    }

    /**
     * Getter for key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Setter for key
     *
     * @param string $key
     *
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Getter for setter
     *
     * @return string
     */
    public function getSetter(): string
    {
        return $this->setter;
    }

    /**
     * Setter for setter
     *
     * @param string $setter
     *
     * @return self
     */
    public function setSetter(string $setter): self
    {
        $this->setter = $setter;
        return $this;
    }
}
