<?php

namespace SOW\TranslationBundle\Tests\Fixtures\AttributedClasses;

use SOW\TranslationBundle\Attribute\Translation;
use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\TranslatableOtherTranslationsTrait;

/**
 * Class TestObject
 *
 * @package SOW\TranslationBundle\Tests\Fixtures\AttributedClasses
 */
class TestObject extends AbstractClass implements Translatable
{
    use TranslatableOtherTranslationsTrait;

    #[Translation(key: "firstname")]
    private string $firstname;

    #[Translation(key: "lastname", setter: "setOtherName")]
    private string $lastname;

    private mixed $notTranslatedProperty;

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setOtherName(string $otherName): self
    {
        $this->lastname = $otherName;
        return $this;
    }

    public function getNotTranslatedProperty(): mixed
    {
        return $this->notTranslatedProperty;
    }

    public function setNotTranslatedProperty($notTranslatedProperty): self
    {
        $this->notTranslatedProperty = $notTranslatedProperty;
        return $this;
    }

    public function getEntityName(): string
    {
        return self::class;
    }

    public function getId(): string
    {
        return '1';
    }
}
