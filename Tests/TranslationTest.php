<?php
/**
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
        $this->assertEquals('name', $translation->getKey());
        $this->assertEquals('setName', $translation->getSetter());
        $this->assertEquals('name', (string)$translation);
    }

    public function testEditTranslation()
    {
        $translation = new Translation('name', 'setName');
        $translation->setKey('new_name');
        $translation->setSetter('setNewName');
        $this->assertEquals('new_name', $translation->getKey());
        $this->assertEquals('setNewName', $translation->getSetter());
        $this->assertEquals('new_name', (string)$translation);
    }

    public function testSerializeTranslation()
    {
        $translation1 = new Translation('name', 'setName');
        $serializedTranslation = serialize($translation1);
        $translation2 = unserialize($serializedTranslation);
        $this->assertTrue($translation2 instanceof Translation);
        $this->assertEquals($translation2->getKey(), $translation1->getKey());
        $this->assertEquals($translation2->getSetter(), $translation1->getSetter());
    }
}
