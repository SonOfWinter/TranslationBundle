<?php

namespace SOW\TranslationBundle\Tests\Fixtures\AttributedClasses;

use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\TranslatableOtherTranslationsTrait;
use SOW\TranslationBundle\Attribute\Translation;

/**
 * Class TestObjectTwo
 *
 * @package SOW\TranslationBundle\Tests\Fixtures\AttributedClasses
 */
class TestObjectTwo extends AbstractClass implements Translatable
{
    use TranslatableOtherTranslationsTrait;

    #[Translation(key: "firstname")]
    private string $firstname;

    #[Translation(key: "lastname")]
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

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
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

    /**
     * getEntityName
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return self::class;
    }

    public function getId(): string
    {
        return '2';
    }
}
