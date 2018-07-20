<?php

/**
 * TranslationGroupTest
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests\Entity
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Tests\Fixtures\Translation\Translation;
use SOW\TranslationBundle\Entity\TranslationGroup;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObject;

/**
 * Class TranslationGroupTest
 *
 * @package SOW\TranslationBundle\Tests\Entity
 */
class TranslationGroupTest extends TestCase
{
    public function testSetterAndGetterTranslationGroup()
    {
        $testObject = new TestObject();
        $translationGroup = new TranslationGroup($testObject, 'fr');
        $this->assertTrue($translationGroup instanceof TranslationGroup);
        $this->assertEquals($translationGroup->getLang(), 'fr');
        $this->assertEquals($translationGroup->getTranslatable(), $testObject);
        $this->assertEquals($translationGroup->getKey('name'), null);
        $this->assertEquals($translationGroup->getTranslations(), []);
    }

    public function testAddTranslation()
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
        $this->assertEquals($translationGroup->getKey('name'), $translation);
        $this->assertEquals($translationGroup->getTranslations(), ['name' => $translation]);
    }

    public function testRemoveTranslation()
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
        $this->assertEquals($translationGroup->getKey('name'), $translation);
        $translationGroup->removeTranslations($translation);
        $this->assertEquals($translationGroup->getKey('name'), null);
        $this->assertEquals($translationGroup->getTranslations(), []);
    }

    public function testChangeTranslationGroup()
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
        $this->assertEquals($translationGroup->getLang(), 'en');
        $this->assertEquals($translationGroup->getKey('name'), $translation);
    }
}
