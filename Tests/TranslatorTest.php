<?php

/**
 * TODO File description
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\TranslationBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use SOW\TranslationBundle\Entity\Translation;
use SOW\TranslationBundle\Entity\TranslationGroup;
use SOW\TranslationBundle\Service\TranslationService;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\WrongTestObject;
use SOW\TranslationBundle\TranslationCollection;
use SOW\TranslationBundle\Translator;

/**
 * Class TranslatorTest
 *
 * @package SOW\TranslationBundle\Tests
 */
class TranslatorTest extends TestCase
{
    /**
     * @var TestObject
     */
    protected $testObject;

    protected $logger;

    protected $loader;

    protected $translationService;

    private $translationAnnotationClass = 'SOW\\TranslationBundle\\Annotation\\Translation';

    public function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->reader = new AnnotationReader();
        $this->loader = $this->getClassLoader($this->reader);
        $this->translationService = $this->getMockBuilder(TranslationService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->testObject = new TestObject();
    }

    public function getClassLoader($reader)
    {
        return $this->getMockBuilder(
            'SOW\TranslationBundle\Loader\AnnotationClassLoader'
        )->setConstructorArgs([$reader, $this->translationAnnotationClass])
            ->getMockForAbstractClass();
    }

    /**
     * @expectedException SOW\TranslationBundle\Exception\TranslatorConfigurationException
     * @expectedExceptionMessage The Translator is not configured
     */
    public function testGetCollectionWithoutResource()
    {
        $translator = new Translator($this->translationService, $this->loader, $this->logger);
        $this->assertTrue($translator instanceof Translator);
        $translator->getTranslationCollection();
    }

    public function testGetCollectionWithResource()
    {
        $translator = new Translator($this->translationService, $this->loader, $this->logger);
        $this->assertTrue($translator instanceof Translator);
        $translator->setResource(get_class($this->testObject));
        $collection = $translator->getTranslationCollection();
        $this->assertTrue($collection instanceof TranslationCollection);
        $this->assertEquals($collection->count(), 2);
    }

    public function testGetTranslationGroupForLang()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('FirstName')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('LastName')
            ->setEntityId($this->testObject->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName]));
        $translator = new Translator($this->translationService, $this->loader, $this->logger);
        $this->assertTrue($translator instanceof Translator);
        $translator->setResource(get_class($this->testObject));
        $translationGroup = $translator->getTranslationGroupForLang($this->testObject, 'fr');
        $this->assertTrue($translationGroup instanceof TranslationGroup);
        $this->assertTrue(in_array($translationFirstName, $translationGroup->getTranslations()));
        $this->assertTrue(in_array($translationLastName, $translationGroup->getTranslations()));
    }

    public function testSetTranslationForLangAndValue()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('FirstName')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('edit')
            ->will($this->returnValue($translationFirstName));
        $translator = new Translator($this->translationService, $this->loader, $this->logger);
        $this->assertTrue($translator instanceof Translator);
        $translation = $translator->setTranslationForLangAndValue($this->testObject, 'fr', 'firstName', 'FirstName');
        $this->assertEquals($translation, $translationFirstName);
    }

    public function testSetTranslationForLangAndValues()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('FirstName')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('LastName')
            ->setEntityId($this->testObject->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->exactly(2))
            ->method('edit')
            ->willReturnOnConsecutiveCalls(
                $this->returnValue($translationFirstName),
                $this->returnValue($translationLastName)
            );
        $translator = new Translator($this->translationService, $this->loader, $this->logger);
        $translator->setResource(get_class($this->testObject));
        $this->assertTrue($translator instanceof Translator);
        $translationGroup = $translator->setTranslationForLangAndValues(
            $this->testObject,
            'fr',
            ['firstname' => 'FirstName', 'lastname' => 'LastName']
        );
        $this->assertTrue($translationGroup instanceof TranslationGroup);
        $this->assertEquals(count($translationGroup->getTranslations()), 2);
    }

    public function testTranslateWithResource()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('new FirstName')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('new LastName')
            ->setEntityId($this->testObject->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName]));
        $translator = new Translator($this->translationService, $this->loader, $this->logger);
        $translator->setResource(get_class($this->testObject));
        $translator->translate($this->testObject, 'fr');
        $this->assertEquals($this->testObject->getFirstname(), 'new FirstName');
        $this->assertEquals($this->testObject->getLastname(), 'new LastName');
    }

    public function testTranslateWithoutResource()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('new FirstName')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('new LastName')
            ->setEntityId($this->testObject->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName]));
        $translator = new Translator($this->translationService, $this->loader, $this->logger);
        $translator->translate($this->testObject, 'fr');
        $this->assertEquals($this->testObject->getFirstname(), 'new FirstName');
        $this->assertEquals($this->testObject->getLastname(), 'new LastName');
    }

    /**
     * @expectedException SOW\TranslationBundle\Exception\TranslatableConfigurationException
     * @expectedExceptionMessage The Entity is misconfigured
     */
    public function testTranslateWithMisconfiguredObject()
    {
        $wrongObject = new WrongTestObject();
        $translationFirstName = new Translation();
        $translationFirstName->setValue('new FirstName')
            ->setEntityId($wrongObject->getId())
            ->setKey("firstname")
            ->setEntityName($wrongObject->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('new LastName')
            ->setEntityId($wrongObject->getId())
            ->setKey("lastname")
            ->setEntityName($wrongObject->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName]));
        $translator = new Translator($this->translationService, $this->loader, $this->logger);
        $translator->translate($wrongObject, 'fr');
    }
}
