<?php

namespace SOW\TranslationBundle\Tests\Fixtures\AttributedClasses;

use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\TranslatableOtherTranslationsTrait;
use SOW\TranslationBundle\Attribute\Translation;

/**
 * Class TestObject
 *
 * @package SOW\TranslationBundle\Tests\Fixtures\AttributedClasses
 */
class TestAttributeObject extends AbstractClass implements Translatable
{
    use TranslatableOtherTranslationsTrait;

     #[Translation(key: "firstname")]
    private string $firstname = '';

    #[Translation(key: "lastname", setter: "setOtherName")]
    private string $lastname = '';

    /**
     * @var mixed
     */
    private $notTranslatedProperty;

    /**
     * getFirstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * setFirstname
     *
     * @param string $firstname
     *
     * @return TestObject
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * getLastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * setOtherName
     *
     * @param string $othername
     *
     * @return TestObject
     */
    public function setOtherName(string $othername): self
    {
        $this->lastname = $othername;
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
     * getEntityName
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return self::class;
    }

    /**
     * getId
     *
     * @return string
     */
    public function getId(): string
    {
        return '1';
    }
}
