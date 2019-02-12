<?php

/**
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use SOW\TranslationBundle\Entity\Translatable;
use SOW\TranslationBundle\Entity\Translation;
use SOW\TranslationBundle\Loader\AnnotationClassLoader;
use SOW\TranslationBundle\Entity\TranslationGroup;
use SOW\TranslationBundle\Service\TranslationService;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObjectThree;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObjectTwo;
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

    private $langs = ['fr', 'en'];

    public function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $reader = new AnnotationReader();
        $this->loader = $this->getClassLoader($reader);
        $this->translationService = $this->getMockBuilder(TranslationService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->testObject = new TestObject();
    }

    public function getClassLoader($reader): MockObject
    {
        return $this->getMockBuilder(AnnotationClassLoader::class)
            ->setConstructorArgs([$reader, $this->translationAnnotationClass])
            ->getMockForAbstractClass();
    }

    /**
     * @expectedException SOW\TranslationBundle\Exception\TranslatorConfigurationException
     * @expectedExceptionMessage The Translator is not configured
     */
    public function testGetCollectionWithoutResource()
    {
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $this->assertTrue($translator instanceof Translator);
        $translator->getTranslationCollection();
    }

    public function testGetCollectionWithResource()
    {
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
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
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
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
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
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
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
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


    public function testSetTranslationForLangAndValuesWithAnotherResource()
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
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);

        $tot = new TestObjectTwo();
        $translator->setResource(get_class($tot));
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
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $translator->setResource(get_class($this->testObject));
        $translator->translate($this->testObject, 'fr');
        $this->assertEquals($this->testObject->getFirstname(), 'new FirstName');
        $this->assertEquals($this->testObject->getLastname(), 'new LastName');
    }

    /**
     * @expectedException SOW\TranslationBundle\Exception\TranslatableConfigurationException
     * @expectedExceptionMessage The Entity is misconfigured
     */
    public function testTranslateWithResourceAndWrongSetter()
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
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $tot = new TestObjectThree();
        $translator->setResource(get_class($tot));
        $translator->translate($tot, 'fr');
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
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
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
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $translator->translate($wrongObject, 'fr');
    }

    public function testRemoveWithFlush()
    {
        $translation = new Translation();
        $this->translationService->expects($this->once())
            ->method('remove')
            ->will($this->returnValue(true));
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $result = $translator->remove($translation, true);
        $this->assertTrue($result);
    }

    public function testRemoveByObjectKeyAndLangWithFlush()
    {
        $this->translationService->expects($this->once())
            ->method('removeByObjectKeyAndLang')
            ->will($this->returnValue(true));
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $result = $translator->removeByObjectKeyAndLang($this->testObject, 'name', 'fr', true);
        $this->assertTrue($result);
    }

    public function testRemoveAllForTranslatableWithFlush()
    {
        $this->translationService->expects($this->once())
            ->method('removeAllForTranslatable')
            ->will($this->returnValue(true));
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $result = $translator->removeAllForTranslatable($this->testObject, true);
        $this->assertTrue($result);
    }

    public function testRemoveAllByKeyWithFlush()
    {
        $this->translationService->expects($this->once())
            ->method('removeAllByKey')
            ->will($this->returnValue(true));
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $result = $translator->removeAllByKey('name', true);
        $this->assertTrue($result);
    }

    public function testcheckTranslation()
    {
        $this->translationService->expects($this->once())
            ->method('checkTranslation')
            ->will($this->returnValue(true));
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $result = $translator->checkTranslation($this->testObject, 'name', 'fr');
        $this->assertTrue($result);
    }

    public function testTranslateForLangsWithResource()
    {
        $translationFirstNameFr = new Translation();
        $translationFirstNameFr->setValue('nouveau prénom')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $translationLastNameFr = new Translation();
        $translationLastNameFr->setValue('nouveau nom')
            ->setEntityId($this->testObject->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $translationFirstNameEn = new Translation();
        $translationFirstNameEn->setValue('new FirstName')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('en');
        $translationLastNameEn = new Translation();
        $translationLastNameEn->setValue('new LastName')
            ->setEntityId($this->testObject->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('en');
        $this->translationService->expects($this->exactly(2))
            ->method('findAllForObjectWithLang')
            ->will(
                $this->onConsecutiveCalls(
                    [$translationFirstNameFr, $translationLastNameFr],
                    [$translationFirstNameEn, $translationLastNameEn]
                )
            );
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $translator->setResource(get_class($this->testObject));
        $result = $translator->translateForLangs($this->testObject, ['fr', 'en', 'it']);
        $this->assertTrue(is_array($result));
        $this->assertCount(3, $result);
        $this->assertTrue(array_key_exists('fr', $result));
        $this->assertTrue(array_key_exists('en', $result));
        $this->assertTrue(array_key_exists('en', $result));
        $this->assertTrue($result['fr'] instanceof Translatable);
        $this->assertTrue($result['en'] instanceof Translatable);
        $this->assertNull($result['it']);
        $this->assertEquals($result['en']->getFirstname(), 'new FirstName');
        $this->assertEquals($result['en']->getLastname(), 'new LastName');
    }


    public function testSetTranslations()
    {
        $translationFirstNameFr = new Translation();
        $translationFirstNameFr->setValue('Prénom')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $translationLastNameFr = new Translation();
        $translationLastNameFr->setValue('Nom')
            ->setEntityId($this->testObject->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('fr');
        $translationFirstNameEn = new Translation();
        $translationFirstNameEn->setValue('FirstName')
            ->setEntityId($this->testObject->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('en');
        $translationLastNameEn = new Translation();
        $translationLastNameEn->setValue('LastName')
            ->setEntityId($this->testObject->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObject->getEntityName())
            ->setLang('en');
        $this->translationService->expects($this->exactly(4))
            ->method('edit')
            ->willReturnOnConsecutiveCalls(
                $this->returnValue($translationFirstNameFr),
                $this->returnValue($translationLastNameFr),
                $this->returnValue($translationFirstNameEn),
                $this->returnValue($translationLastNameEn)
            );
        $this->logger->expects($this->once())->method('info');
        $this->translationService->expects($this->once())->method('flush');
        $translator = new Translator($this->translationService, $this->loader, $this->langs, $this->logger);
        $translator->setResource(get_class($this->testObject));
        $this->assertTrue($translator instanceof Translator);
        $translationGroups = $translator->setTranslations(
            $this->testObject,
            [
                'fr' => ['firstname' => 'Prénom', 'lastname' => 'Nom'],
                'en' => ['firstname' => 'FirstName', 'lastname' => 'LastName'],
                'de' => ['firstname' => 'azerty', 'lastname' => 'azerty']
            ],
            true
        );
        $this->assertTrue(is_array($translationGroups));
        $this->assertCount(2, $translationGroups);
        $this->assertTrue($translationGroups['fr'] instanceof TranslationGroup);
        $this->assertTrue($translationGroups['en'] instanceof TranslationGroup);
        $this->assertEquals(count($translationGroups['fr']->getTranslations()), 2);
        $this->assertEquals(count($translationGroups['en']->getTranslations()), 2);
    }
}
