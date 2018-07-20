<?php

/**
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests;

use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Translation;

/**
 * Class TranslationTest
 *
 * @package SOW\TranslationBundle\Tests
 */
class TranslationTest extends TestCase
{
    public function testCreateTranslation()
    {
        $translation = new Translation('name', 'setName');
        $this->assertTrue($translation instanceof Translation);
        $this->assertEquals($translation->getKey(), 'name');
        $this->assertEquals($translation->getSetter(), 'setName');
        $this->assertEquals((string) $translation, 'name');
    }

    public function testEditTranslation()
    {
        $translation = new Translation('name', 'setName');
        $this->assertTrue($translation instanceof Translation);
        $translation->setKey('new_name');
        $translation->setSetter('setNewName');
        $this->assertEquals($translation->getKey(), 'new_name');
        $this->assertEquals($translation->getSetter(), 'setNewName');
        $this->assertEquals((string) $translation, 'new_name');
    }

    public function testSerializeTranslation()
    {
        $serializedTranslation = serialize([
            "key" => 'name',
            "setter" => 'setName'
        ]);
        $translation1 = new Translation('name', 'setName');
        $this->assertTrue($translation1 instanceof Translation);
        $serializedTranslation1 = $translation1->serialize();
        $this->assertEquals(
            $serializedTranslation1,
            $serializedTranslation
        );
        $translation2 = new Translation('name 2', 'setName 2');
        $translation2->unserialize($serializedTranslation);
        $this->assertEquals($translation2->getKey(), 'name');
        $this->assertEquals($translation2->getSetter(), 'setName');
    }
}
