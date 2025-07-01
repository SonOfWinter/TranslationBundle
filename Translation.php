<?php

namespace SOW\TranslationBundle;

/**
 * Class Translation
 *
 * @package SOW\TranslationBundle
 */
class Translation
{
    private string $key;

    private string $setter;

    /**
     * Translation constructor.
     *
     * @param string $key
     * @param string $setter
     */
    public function __construct(string $key = '', string $setter = '')
    {
        $this->key = $key;
        $this->setter = $setter;
    }

    public function __serialize(): array
    {
        return [
            'key' => $this->key,
            'setter' => $this->setter,
        ];
    }

    public function __unserialize(array $data)
    {
        $this->key = $data['key'];
        $this->setter = $data['setter'];
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getKey();
    }
}
