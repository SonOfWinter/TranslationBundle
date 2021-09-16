<?php

/**
 * AttributeClassLoader test
 *
 * @package  SOW\TranslationBundle\Tests\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests\Loader;

use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Loader\AttributeClassLoader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * Class AttributeClassLoaderTest
 *
 * @package SOW\TranslationBundle\Tests\Loader
 */
class AttributeClassLoaderTest extends TestCase
{
    private AttributeClassLoader $loader;

    private $translationAttributeClass = 'SOW\\TranslationBundle\\Attribute\\Translation';

    protected function setUp(): void
    {
        parent::setUp();
        $this->loader = $this->getClassLoader();
    }

    protected function setObjectAttribute($object, $attributeName, $value)
    {
        $reflection = new \ReflectionObject($object);
        $property = $reflection->getProperty($attributeName);
        $property->setAccessible(true);
        $property->setValue(
            $object,
            $value
        );
    }

    public function getClassLoader()
    {
        return $this->getMockBuilder(
            'SOW\TranslationBundle\Loader\AttributeClassLoader'
        )->setConstructorArgs([$this->translationAttributeClass])
            ->getMockForAbstractClass();
    }

    # setTranslationAttributeClass

    public function testChangeAttributeClass()
    {
        $newClass = 'SOW\\TranslationBundle\\Tests\\Fixtures\\AttributedClasses\\TestAttributeObject';
        $this->loader->setTranslationAttributeClass($newClass);
        $reflection = new \ReflectionObject($this->loader);
        $property = $reflection->getProperty('translationAttributeClass');
        $property->setAccessible(true);
        $this->assertEquals(
            $newClass,
            $property->getValue($this->loader)
        );
    }

    # load

    public function testLoadWrongClass()
    {
        static::expectException('\InvalidArgumentException');
        $this->loader->load('WrongClass');
    }

    public function testLoadAbstractClass()
    {
        static::expectException('\InvalidArgumentException');
        $this->loader->load(
            'SOW\TranslationBundle\Tests\Fixtures\AttributedClasses\AbstractClass'
        );
    }

    public function testLoadClass()
    {
        $collection = $this->loader->load(
            'SOW\TranslationBundle\Tests\Fixtures\AttributedClasses\TestAttributeObject'
        );
        $this->assertEquals(
            2,
            $collection->count()
        );
    }

    public function testSupportsChecksTypeIfSpecified()
    {
        $this->assertTrue(
            $this->loader->supports(
                'class',
                'attribute'
            )
        );
        $this->assertFalse(
            $this->loader->supports(
                'class',
                'foo'
            )
        );
    }

    public function testGetResolverDoesNothing()
    {
        $this->assertTrue(empty($this->loader->getResolver()));
    }

    public function testSetResolverDoesNothing()
    {
        $lri = $this->createMock(LoaderResolverInterface::class);
        $this->assertTrue(empty($this->loader->setResolver($lri)));
    }
}
