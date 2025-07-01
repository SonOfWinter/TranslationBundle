<?php

namespace SOW\TranslationBundle\Tests;

use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Tests\Fixtures\AttributedClasses\TestObject;
use SOW\TranslationBundle\Translation;
use SOW\TranslationBundle\TranslationCollection;
use Symfony\Component\Config\Resource\FileResource;

/**
 * Class TranslationCollectionTest
 *
 * @package SOW\TranslationBundle\Tests
 */
class TranslationCollectionTest extends TestCase
{
    protected TestObject $testObject;

    public function setUp(): void
    {
        $this->testObject = new TestObject();
    }

    public function testCreateTranslationCollection(): void
    {
        $translationCollection = new TranslationCollection();
        $this->assertTrue($translationCollection instanceof TranslationCollection);
        $this->assertEquals(count($translationCollection), 0);
        $this->assertEquals($translationCollection->all(), []);
    }

    public function testAddElementInTranslationCollection(): void
    {
        $translationCollection = new TranslationCollection();
        $class = new \ReflectionClass($this->testObject);
        $resource = new FileResource($class->getFileName());
        $translationCollection->addResource($resource);
        $translation = new Translation('name', 'setName');
        $this->assertTrue($translationCollection instanceof TranslationCollection);
        $translationCollection->add($translation);
        $iterator = $translationCollection->getIterator();
        $this->assertEquals(count($translationCollection), 1);
        $this->assertEquals($translationCollection->all(), ['name' => $translation]);
        $this->assertEquals($translationCollection->get('name'), $translation);
        $this->assertEquals($translationCollection->getResources(), [$resource]);
        $this->assertEquals($translationCollection->getKeys(), ['name']);
        $this->assertTrue($iterator instanceof \ArrayIterator);
        $this->assertEquals(count($iterator), 1);
    }

    public function testRemoveElementInTranslationCollection(): void
    {
        $translationCollection = new TranslationCollection();
        $class = new \ReflectionClass($this->testObject);
        $resource = new FileResource($class->getFileName());
        $translationCollection->addResource($resource);
        $translation = new Translation('name', 'setName');
        $this->assertTrue($translationCollection instanceof TranslationCollection);
        $translationCollection->add($translation);
        $this->assertEquals(count($translationCollection), 1);
        $this->assertEquals($translationCollection->all(), ['name' => $translation]);
        $translationCollection->remove('name');
        $this->assertEquals(count($translationCollection), 0);
        $this->assertEquals($translationCollection->all(), []);
    }

    public function testAddOtherCollectionInTranslationCollection(): void
    {
        $class = new \ReflectionClass($this->testObject);
        $resource = new FileResource($class->getFileName());
        $translationCollection1 = new TranslationCollection();
        $translationCollection1->addResource($resource);
        $translation1 = new Translation('name', 'setName');
        $translationCollection1->add($translation1);
        $this->assertTrue($translationCollection1 instanceof TranslationCollection);
        $translationCollection2 = new TranslationCollection();
        $translationCollection2->addResource($resource);
        $translation2 = new Translation('age', 'setAge');
        $translationCollection2->add($translation2);
        $this->assertTrue($translationCollection2 instanceof TranslationCollection);
        $translationCollection1->addCollection($translationCollection2);
        $this->assertEquals(count($translationCollection1), 2);
        $this->assertEquals($translationCollection1->getKeys(), ['name', 'age']);
        $this->assertEquals(
            $translationCollection1->all(),
            ['name' => $translation1, 'age' => $translation2]
        );
        $this->assertEquals($translationCollection1->getResources(), [$resource]);
    }
}
