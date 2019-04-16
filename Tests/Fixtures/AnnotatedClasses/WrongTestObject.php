<?php

namespace SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses;

use SOW\TranslationBundle\Annotation as Translation;
use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\TranslatableOtherTranslationsTrait;

/**
 * Class WrongTestObject
 *
 * @package SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses
 */
class WrongTestObject extends AbstractClass implements Translatable
{
    use TranslatableOtherTranslationsTrait;

    /**
     * @var string
     *
     * @Translation\Translation(key="firstname")
     */
    private $firstname;

    /**
     * @var string
     *
     * @Translation\Translation(key="lastname", setter="setSomeThing")
     */
    private $lastname;

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
     * @return WrongTestObject
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
     * @return WrongTestObject
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
