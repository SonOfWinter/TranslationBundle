<?php

/**
 * AbstractTranslation abstract class
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Entity
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractTranslation
 *
 * @package SOW\TranslationBundle\Entity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractTranslation
{
    /**
     * @var string
     *
     * @ORM\Column(name="`key`", type="string")
     * @ORM\Id
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="`lang`", type="string")
     * @ORM\Id
     */
    protected $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="`entity_name`", type="string")
     * @ORM\Id
     */
    protected $entityName;

    /**
     * @var string
     *
     * @ORM\Column(name="`entity_id`", type="string")
     * @ORM\Id
     */
    protected $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="`value`", type="text")
     */
    protected $value;

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
     * Getter for lang
     *
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * Setter for lang
     *
     * @param string $lang
     *
     * @return self
     */
    public function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Getter for entityName
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * Setter for entityName
     *
     * @param string $entityName
     *
     * @return self
     */
    public function setEntityName(string $entityName): self
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * Getter for entityId
     *
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    /**
     * Setter for entityId
     *
     * @param string $entityId
     *
     * @return self
     */
    public function setEntityId(string $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * Getter for value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Setter for value
     *
     * @param string $value
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
