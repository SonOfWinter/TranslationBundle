<?php

/**
 * AnnotationClassLoader test
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Loader\AnnotationClassLoader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * Class AnnotationClassLoaderTest
 *
 * @package SOW\TranslationBundle\Tests\Loader
 */
class AnnotationClassLoaderTest extends TestCase
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var AnnotationClassLoader
     */
    private $loader;

    private $translationAnnotationClass = 'SOW\\TranslationBundle\\Annotation\\Translation';

    protected function setUp(): void
    {
        parent::setUp();
        $this->reader = new AnnotationReader();
        $this->loader = $this->getClassLoader($this->reader);
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

    public function getReader()
    {
        return $this->getMockBuilder('Doctrine\Common\Annotations\Reader')
            ->disableOriginalConstructor()->getMock();
    }

    public function getClassLoader($reader)
    {
        return $this->getMockBuilder(
            'SOW\TranslationBundle\Loader\AnnotationClassLoader'
        )->setConstructorArgs([$reader, $this->translationAnnotationClass])
            ->getMockForAbstractClass();
    }

    # setTranslationAnnotationClass

    public function testChangeAnnotationClass()
    {
        $newClass = 'SOW\\TranslationBundle\\Tests\\Fixtures\\AnnotatedClasses\\TestObject';
        $this->loader->setTranslationAnnotationClass($newClass);
        $reflection = new \ReflectionObject($this->loader);
        $property = $reflection->getProperty('translationAnnotationClass');
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
            'SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\AbstractClass'
        );
    }

    public function testLoadClass()
    {
        $collection = $this->loader->load(
            'SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObject'
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
                'annotation'
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
