<?php

/**
 * Translation test
 *
 * PHP Version 7.1
 *
 * @package  SOW\Translation\Tests\Annotation
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests\Annotation;

use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Annotation\Translation;

/**
 * Class TranslationTest
 *
 * @package SOW\Translation\Tests\Annotation
 */
class TranslationTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testInvalidRouteParameter()
    {
        new Translation(['foo' => 'bar']);
    }

    public function testValidNameAndSetterParameter()
    {
        $translation = new Translation(['key' => 'test', 'setter' => 'getTest']);
        $this->assertTrue($translation instanceof Translation);
        $this->assertEquals(
            'test',
            $translation->getKey()
        );
        $this->assertEquals(
            'getTest',
            $translation->getSetter()
        );
    }

    public function testValidNameParameter()
    {
        $translation = new Translation(['key' => 'test']);
        $this->assertTrue($translation instanceof Translation);
        $this->assertEquals(
            'test',
            $translation->getKey()
        );
        $this->assertNull($translation->getSetter());
    }
}
