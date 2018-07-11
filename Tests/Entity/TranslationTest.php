<?php

/**
 * TranslationTest
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests\Entity
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Entity\Translation;
use SOW\TranslationBundle\Entity\TranslationGroup;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObject;

/**
 * Class TranslationTest
 *
 * @package SOW\TranslationBundle\Tests\Entity
 */
class TranslationTest extends TestCase
{
    public function testSetterAndGetterTranslation()
    {
        $translation = new Translation();
        $translation->setValue('some name')
            ->setEntityId('1')
            ->setKey('name')
            ->setEntityName('someEntity')
            ->setLang('fr');

        $this->assertTrue($translation instanceof Translation);
        $this->assertEquals($translation->getValue(), 'some name');
        $this->assertEquals($translation->getEntityId(), '1');
        $this->assertEquals($translation->getKey(), 'name');
        $this->assertEquals($translation->getEntityName(), 'someEntity');
        $this->assertEquals($translation->getLang(), 'fr');
    }
}
