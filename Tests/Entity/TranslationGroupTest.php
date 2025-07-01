<?php
/**
 * TranslationGroupTest
 *
 * @package  SOW\TranslationBundle\Tests\Entity
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Entity\Translation;
use SOW\TranslationBundle\Entity\TranslationGroup;
use SOW\TranslationBundle\Tests\Fixtures\AttributedClasses\TestObject;

/**
 * Class TranslationGroupTest
 *
 * @package SOW\TranslationBundle\Tests\Entity
 */
class TranslationGroupTest extends TestCase
{
    public function testSetterAndGetterTranslationGroup(): void
    {
        $testObject = new TestObject();
        $translationGroup = new TranslationGroup($testObject, 'fr');
        $this->assertTrue($translationGroup instanceof TranslationGroup);
        $this->assertEquals('fr', $translationGroup->getLang());
        $this->assertEquals($testObject, $translationGroup->getTranslatable());
        $this->assertEquals(null, $translationGroup->getKey('name'));
        $this->assertEquals([], $translationGroup->getTranslations());
    }

    public function testAddTranslation(): void
    {
        $translation = new Translation();
        $translation->setValue('some name')
            ->setEntityId('1')
            ->setKey('name')
            ->setEntityName('someEntity')
            ->setLang('fr');
        $testObject = new TestObject();
        $translationGroup = new TranslationGroup($testObject, 'fr');
        $translationGroup->addTranslation($translation);
        $this->assertEquals($translation, $translationGroup->getKey('name'));
        $this->assertEquals(['name' => $translation], $translationGroup->getTranslations());
    }

    public function testRemoveTranslation(): void
    {
        $translation = new Translation();
        $translation->setValue('some name')
            ->setEntityId('1')
            ->setKey('name')
            ->setEntityName('someEntity')
            ->setLang('fr');
        $testObject = new TestObject();
        $translationGroup = new TranslationGroup($testObject, 'fr');
        $translationGroup->addTranslation($translation);
        $this->assertEquals($translation, $translationGroup->getKey('name'));
        $translationGroup->removeTranslations($translation);
        $this->assertEquals(null, $translationGroup->getKey('name'));
        $this->assertEquals([], $translationGroup->getTranslations());
    }

    public function testChangeTranslationGroup(): void
    {
        $translation = new Translation();
        $translation->setValue('some name')
            ->setEntityId('1')
            ->setKey('name')
            ->setEntityName('someEntity')
            ->setLang('fr');
        $translations = ["name" => $translation];
        $testObject = new TestObject();
        $translationGroup = new TranslationGroup($testObject, 'fr');
        $translationGroup->setTranslations($translations);
        $translationGroup->setLang('en');
        $this->assertEquals('en', $translationGroup->getLang());
        $this->assertEquals($translation, $translationGroup->getKey('name'));
    }
}
