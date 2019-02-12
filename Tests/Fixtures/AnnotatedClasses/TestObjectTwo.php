<?php

namespace SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses;

use SOW\TranslationBundle\Annotation as Translation;
use SOW\TranslationBundle\Entity\Translatable;

/**
 * Class TestObjectTwo
 *
 * @package SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses
 */
class TestObjectTwo extends AbstractClass implements Translatable
{
    /**
     * @var string
     * @Translation\Translation(key="firstname")
     */
    private $firstname;

    /**
     * @var string
     * @Translation\Translation(key="lastname")
     */
    private $lastname;

    /**
     * @var mixed
     */
    private $notTranslatedProperty;

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Setter for lastname
     *
     * @param string $lastname
     *
     * @return self
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Getter for notTranslatedProperty
     *
     * @return mixed
     */
    public function getNotTranslatedProperty()
    {
        return $this->notTranslatedProperty;
    }

    /**
     * Setter for notTranslatedProperty
     *
     * @param mixed $notTranslatedProperty
     *
     * @return self
     */
    public function setNotTranslatedProperty($notTranslatedProperty): self
    {
        $this->notTranslatedProperty = $notTranslatedProperty;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return self::class;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return '1';
    }
}
