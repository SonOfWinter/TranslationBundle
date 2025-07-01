<?php

namespace SOW\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractTranslation
 *
 * @package SOW\TranslationBundle\Entity
 * @ORM\MappedSuperclass
 */
abstract class AbstractTranslation
{
    /**
     * @ORM\Column(name="`key`", type="string", length=150)
     * @ORM\Id
     */
    protected string $key;

    /**
     * @ORM\Column(name="`lang`", type="string", length=2)
     * @ORM\Id
     */
    protected string $lang;

    /**
     * @ORM\Column(name="`entity_name`", type="string", length=150)
     * @ORM\Id
     */
    protected string $entityName;

    /**
     * @ORM\Column(name="`entity_id`", type="string", length=36)
     * @ORM\Id
     */
    protected string $entityId;

    /**
     * @ORM\Column(name="`value`", type="text")
     */
    protected string $value;

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
        return $this->value ?? '';
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
