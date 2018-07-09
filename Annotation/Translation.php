<?php

/**
 * Translation annotation class
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Annotation
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Annotation;

/**
 * Class Translation
 *
 * @package SOW\TranslationBundle\Annotation
 *
 * @Annotation
 *
 * @Target("PROPERTY")
 */
class Translation
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $setter;

    /**
     * Translation constructor.
     *
     * @param array $data
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $noUnderscoreKey = str_replace(
                '_',
                '',
                $key
            );
            $method = 'set' . $noUnderscoreKey;
            if (!method_exists(
                $this,
                $method
            )
            ) {
                $message = sprintf(
                    'Unknown property "%s" on annotation "%s".',
                    $key,
                    get_class($this)
                );
                throw new \BadMethodCallException($message);
            }
            $this->$method($value);
        }
    }

    /**
     * Getter for key
     *
     * @return string
     */
    public function getKey()
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
    public function getSetter()
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
