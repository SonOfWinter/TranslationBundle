<?php
/**
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
use SOW\TranslationBundle\Loader\AttributeClassLoader;
use SOW\TranslationBundle\Service\TranslationService;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObjectThree;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObjectTwo;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\WrongTestObject;
use SOW\TranslationBundle\Tests\Fixtures\AttributedClasses\TestAttributeObject;
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
    protected $testObjectAnnotation;

    /** @var TestAttributeObject */
    protected $testObjectAttribute;

    protected $logger;

    protected $annotationClassLoader;

    protected $attributeClassLoader;

    protected $translationService;

    private $translationAnnotationClass = 'SOW\\TranslationBundle\\Annotation\\Translation';

    private $translationAttributeClass = 'SOW\\TranslationBundle\\Attribute\\Translation';

    private $langs = ['fr', 'en'];

    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $reader = new AnnotationReader();
        $this->annotationClassLoader = $this->getAnnotationClassLoader($reader);
        $this->attributeClassLoader = $this->getAttributeClassLoader();
        $this->translationService = $this->getMockBuilder(TranslationService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->testObjectAnnotation = new TestObject();
        $this->testObjectAttribute = new TestAttributeObject();
    }

    public function getAnnotationClassLoader($reader): MockObject
    {
        return $this->getMockBuilder(AnnotationClassLoader::class)
            ->setConstructorArgs([$reader, $this->translationAnnotationClass])
            ->getMockForAbstractClass();
    }

    public function getAttributeClassLoader(): MockObject
    {
        return $this->getMockBuilder(AttributeClassLoader::class)
            ->setConstructorArgs([$this->translationAttributeClass])
            ->getMockForAbstractClass();
    }

    public function testNewTranslatorWithWrongMethod()
    {
        static::expectException('SOW\TranslationBundle\Exception\TranslatorConfigurationException');
        static::expectExceptionMessage('Wrong translator method');
        new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            "Translator",
            $this->logger
        );
    }

    public function testGetCollectionWithoutResource()
    {
        static::expectException('SOW\TranslationBundle\Exception\TranslatorConfigurationException');
        static::expectExceptionMessage('The Translator is not configured');
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $this->assertTrue($translator instanceof Translator);
        $translator->getTranslationCollection();
    }

    public function testGetCollectionWithResource()
    {
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $this->assertTrue($translator instanceof Translator);
        $translator->setResource(get_class($this->testObjectAnnotation));
        $collection = $translator->getTranslationCollection();
        $this->assertTrue($collection instanceof TranslationCollection);
        $this->assertEquals($collection->count(), 2);
    }

    public function testGetTranslationGroupForLang()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('FirstName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('LastName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName]));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $this->assertTrue($translator instanceof Translator);
        $translator->setResource(get_class($this->testObjectAnnotation));
        $translationGroup = $translator->getTranslationGroupForLang($this->testObjectAnnotation, 'fr');
        $this->assertTrue($translationGroup instanceof TranslationGroup);
        $this->assertTrue(in_array($translationFirstName, $translationGroup->getTranslations()));
        $this->assertTrue(in_array($translationLastName, $translationGroup->getTranslations()));
    }

    public function testSetTranslationForLangAndValue()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('FirstName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('edit')
            ->will($this->returnValue($translationFirstName));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $this->assertTrue($translator instanceof Translator);
        $translation = $translator->setTranslationForLangAndValue($this->testObjectAnnotation, 'fr', 'firstName', 'FirstName');
        $this->assertEquals($translation, $translationFirstName);
    }

    public function testSetTranslationForLangAndValues()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('FirstName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('LastName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->exactly(2))
            ->method('edit')
            ->willReturnOnConsecutiveCalls(
                $this->returnValue($translationFirstName),
                $this->returnValue($translationLastName)
            );
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $translator->setResource(get_class($this->testObjectAnnotation));
        $this->assertTrue($translator instanceof Translator);
        $translationGroup = $translator->setTranslationForLangAndValues(
            $this->testObjectAnnotation,
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
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('LastName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->exactly(2))
            ->method('edit')
            ->willReturnOnConsecutiveCalls(
                $this->returnValue($translationFirstName),
                $this->returnValue($translationLastName)
            );
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $tot = new TestObjectTwo();
        $translator->setResource(get_class($tot));
        $this->assertTrue($translator instanceof Translator);
        $translationGroup = $translator->setTranslationForLangAndValues(
            $this->testObjectAnnotation,
            'fr',
            ['firstname' => 'FirstName', 'lastname' => 'LastName']
        );
        $this->assertTrue($translationGroup instanceof TranslationGroup);
        $this->assertEquals(count($translationGroup->getTranslations()), 2);
    }

    public function testAnnotationTranslateWithResource()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('new FirstName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('new LastName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationOther = new Translation();
        $translationOther->setValue('other value')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("other")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName, $translationOther]));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $translator->setResource(get_class($this->testObjectAnnotation));
        $translator->translate($this->testObjectAnnotation, 'fr');
        $this->assertEquals($this->testObjectAnnotation->getFirstname(), 'new FirstName');
        $this->assertEquals($this->testObjectAnnotation->getLastname(), 'new LastName');
        $this->assertCount(1, $this->testObjectAnnotation->getOtherTranslations());
        $this->assertTrue(array_key_exists('other', $this->testObjectAnnotation->getOtherTranslations()));
        $this->assertEquals($this->testObjectAnnotation->getOtherTranslations()['other'], 'other value');
    }

    public function testAnnotationTranslateWithResourceAndWrongSetter()
    {
        static::expectException('SOW\TranslationBundle\Exception\TranslatableConfigurationException');
        static::expectExceptionMessage('The Entity is misconfigured');
        $translationFirstName = new Translation();
        $translationFirstName->setValue('new FirstName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('new LastName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $tot = new WrongTestObject();
        $translator->setResource(get_class($tot));
        $translator->translate($tot, 'fr');
    }

    public function testAnnotationTranslateWithoutResource()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('new FirstName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('new LastName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName]));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $translator->translate($this->testObjectAnnotation, 'fr');
        $this->assertEquals($this->testObjectAnnotation->getFirstname(), 'new FirstName');
        $this->assertEquals($this->testObjectAnnotation->getLastname(), 'new LastName');
    }

    public function testAnnotationTranslateWithMisconfiguredObject()
    {
        static::expectException('SOW\TranslationBundle\Exception\TranslatableConfigurationException');
        static::expectExceptionMessage('The Entity is misconfigured');
        $wrongObject = new WrongTestObject();
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $translator->translate($wrongObject, 'fr');
    }

    public function testAnnotationTranslateAll()
    {
        $testObjectThree = new TestObjectThree();
        $translationFirstNameFr = new Translation();
        $translationFirstNameFr->setValue('nouveau prénom')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastNameFr = new Translation();
        $translationLastNameFr->setValue('nouveau nom')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationOtherFr = new Translation();
        $translationOtherFr->setValue('other value')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("other")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationFirstNameFr3 = new Translation();
        $translationFirstNameFr3->setValue('nouveau prénom3')
            ->setEntityId($testObjectThree->getId())
            ->setKey("firstname")
            ->setEntityName($testObjectThree->getEntityName())
            ->setLang('fr');
        $translationLastNameFr3 = new Translation();
        $translationLastNameFr3->setValue('nouveau nom3')
            ->setEntityId($testObjectThree->getId())
            ->setKey("lastname")
            ->setEntityName($testObjectThree->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findByEntityNameAndLang')
            ->will(
                $this->returnValue(
                    [
                        $translationFirstNameFr,
                        $translationLastNameFr,
                        $translationOtherFr,
                        $translationFirstNameFr3,
                        $translationLastNameFr3,
                    ]
                )
            );
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $translator->setResource(get_class($this->testObjectAnnotation));
        $result = $translator->translateAll(
            $this->testObjectAnnotation->getEntityName(),
            [$this->testObjectAnnotation, $testObjectThree],
            'fr'
        );
        $this->assertTrue(is_array($result));
        $this->assertCount(2, $result);
        $this->assertTrue(array_key_exists($this->testObjectAnnotation->getId(), $result));
        $this->assertTrue(array_key_exists($testObjectThree->getId(), $result));
        $objWithTranslation = $result[$this->testObjectAnnotation->getId()];
        $this->assertEquals($objWithTranslation->getFirstname(), 'nouveau prénom');
        $this->assertEquals($objWithTranslation->getLastname(), 'nouveau nom');
        $this->assertCount(1, $objWithTranslation->getOtherTranslations());
        $this->assertTrue(array_key_exists('other', $objWithTranslation->getOtherTranslations()));
        $this->assertEquals($objWithTranslation->getOtherTranslations()['other'], 'other value');
        $this->assertEquals($result[$testObjectThree->getId()]->getFirstname(), 'nouveau prénom3');
        $this->assertEquals($result[$testObjectThree->getId()]->getLastname(), 'nouveau nom3');
    }
    
    public function testAttributeTranslateWithResource()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('new FirstName')
            ->setEntityId($this->testObjectAttribute->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAttribute->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('new LastName')
            ->setEntityId($this->testObjectAttribute->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAttribute->getEntityName())
            ->setLang('fr');
        $translationOther = new Translation();
        $translationOther->setValue('other value')
            ->setEntityId($this->testObjectAttribute->getId())
            ->setKey("other")
            ->setEntityName($this->testObjectAttribute->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName, $translationOther]));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ATTRIBUTE,
            $this->logger
        );
        $translator->setResource(get_class($this->testObjectAttribute));
        $translator->translate($this->testObjectAttribute, 'fr');
        $this->assertEquals($this->testObjectAttribute->getFirstname(), 'new FirstName');
        $this->assertEquals($this->testObjectAttribute->getLastname(), 'new LastName');
        $this->assertCount(1, $this->testObjectAttribute->getOtherTranslations());
        $this->assertTrue(array_key_exists('other', $this->testObjectAttribute->getOtherTranslations()));
        $this->assertEquals($this->testObjectAttribute->getOtherTranslations()['other'], 'other value');
    }

    public function testAttributeTranslateWithoutResource()
    {
        $translationFirstName = new Translation();
        $translationFirstName->setValue('new FirstName')
            ->setEntityId($this->testObjectAttribute->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAttribute->getEntityName())
            ->setLang('fr');
        $translationLastName = new Translation();
        $translationLastName->setValue('new LastName')
            ->setEntityId($this->testObjectAttribute->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAttribute->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findAllForObjectWithLang')
            ->will($this->returnValue([$translationFirstName, $translationLastName]));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ATTRIBUTE,
            $this->logger
        );
        $translator->translate($this->testObjectAttribute, 'fr');
        $this->assertEquals($this->testObjectAttribute->getFirstname(), 'new FirstName');
        $this->assertEquals($this->testObjectAttribute->getLastname(), 'new LastName');
    }

    public function testAttributeTranslateAll()
    {
        $testObjectThree = new TestObjectThree();
        $translationFirstNameFr = new Translation();
        $translationFirstNameFr->setValue('nouveau prénom')
            ->setEntityId($this->testObjectAttribute->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAttribute->getEntityName())
            ->setLang('fr');
        $translationLastNameFr = new Translation();
        $translationLastNameFr->setValue('nouveau nom')
            ->setEntityId($this->testObjectAttribute->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAttribute->getEntityName())
            ->setLang('fr');
        $translationOtherFr = new Translation();
        $translationOtherFr->setValue('other value')
            ->setEntityId($this->testObjectAttribute->getId())
            ->setKey("other")
            ->setEntityName($this->testObjectAttribute->getEntityName())
            ->setLang('fr');
        $translationFirstNameFr3 = new Translation();
        $translationFirstNameFr3->setValue('nouveau prénom3')
            ->setEntityId($testObjectThree->getId())
            ->setKey("firstname")
            ->setEntityName($testObjectThree->getEntityName())
            ->setLang('fr');
        $translationLastNameFr3 = new Translation();
        $translationLastNameFr3->setValue('nouveau nom3')
            ->setEntityId($testObjectThree->getId())
            ->setKey("lastname")
            ->setEntityName($testObjectThree->getEntityName())
            ->setLang('fr');
        $this->translationService->expects($this->once())
            ->method('findByEntityNameAndLang')
            ->will(
                $this->returnValue(
                    [
                        $translationFirstNameFr,
                        $translationLastNameFr,
                        $translationOtherFr,
                        $translationFirstNameFr3,
                        $translationLastNameFr3,
                    ]
                )
            );
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ATTRIBUTE,
            $this->logger
        );
        $translator->setResource(get_class($this->testObjectAttribute));
        $result = $translator->translateAll(
            $this->testObjectAttribute->getEntityName(),
            [$this->testObjectAttribute, $testObjectThree],
            'fr'
        );
        $this->assertTrue(is_array($result));
        $this->assertCount(2, $result);
        $this->assertTrue(array_key_exists($this->testObjectAttribute->getId(), $result));
        $this->assertTrue(array_key_exists($testObjectThree->getId(), $result));
        $objWithTranslation = $result[$this->testObjectAttribute->getId()];
        $this->assertEquals($objWithTranslation->getFirstname(), 'nouveau prénom');
        $this->assertEquals($objWithTranslation->getLastname(), 'nouveau nom');
        $this->assertCount(1, $objWithTranslation->getOtherTranslations());
        $this->assertTrue(array_key_exists('other', $objWithTranslation->getOtherTranslations()));
        $this->assertEquals($objWithTranslation->getOtherTranslations()['other'], 'other value');
        $this->assertEquals($result[$testObjectThree->getId()]->getFirstname(), 'nouveau prénom3');
        $this->assertEquals($result[$testObjectThree->getId()]->getLastname(), 'nouveau nom3');
    }

    public function testRemoveWithFlush()
    {
        $translation = new Translation();
        $this->translationService->expects($this->once())
            ->method('remove')
            ->will($this->returnValue(true));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $result = $translator->remove($translation, true);
        $this->assertTrue($result);
    }

    public function testRemoveByObjectKeyAndLangWithFlush()
    {
        $this->translationService->expects($this->once())
            ->method('removeByObjectKeyAndLang')
            ->will($this->returnValue(true));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $result = $translator->removeByObjectKeyAndLang($this->testObjectAnnotation, 'name', 'fr', true);
        $this->assertTrue($result);
    }

    public function testRemoveAllForTranslatableWithFlush()
    {
        $this->translationService->expects($this->once())
            ->method('removeAllForTranslatable')
            ->will($this->returnValue(true));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $result = $translator->removeAllForTranslatable($this->testObjectAnnotation, true);
        $this->assertTrue($result);
    }

    public function testRemoveAllByKeyWithFlush()
    {
        $this->translationService->expects($this->once())
            ->method('removeAllByKey')
            ->will($this->returnValue(true));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $result = $translator->removeAllByKey('name', true);
        $this->assertTrue($result);
    }

    public function testcheckTranslation()
    {
        $this->translationService->expects($this->once())
            ->method('checkTranslation')
            ->will($this->returnValue(true));
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $result = $translator->checkTranslation($this->testObjectAnnotation, 'name', 'fr');
        $this->assertTrue($result);
    }

    public function testTranslateForLangsWithResource()
    {
        $translationFirstNameFr = new Translation();
        $translationFirstNameFr->setValue('nouveau prénom')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastNameFr = new Translation();
        $translationLastNameFr->setValue('nouveau nom')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationFirstNameEn = new Translation();
        $translationFirstNameEn->setValue('new FirstName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('en');
        $translationLastNameEn = new Translation();
        $translationLastNameEn->setValue('new LastName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('en');
        $this->translationService->expects($this->exactly(2))
            ->method('findAllForObjectWithLang')
            ->will(
                $this->onConsecutiveCalls(
                    [$translationFirstNameFr, $translationLastNameFr],
                    [$translationFirstNameEn, $translationLastNameEn]
                )
            );
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $translator->setResource(get_class($this->testObjectAnnotation));
        $result = $translator->translateForLangs($this->testObjectAnnotation, ['fr', 'en', 'it']);
        $this->assertTrue(is_array($result));
        $this->assertCount(2, $result);
        $this->assertTrue(array_key_exists('fr', $result));
        $this->assertTrue(array_key_exists('en', $result));
        $this->assertFalse(array_key_exists('it', $result));
        $this->assertTrue($result['fr'] instanceof Translatable);
        $this->assertTrue($result['en'] instanceof Translatable);
        $this->assertEquals($result['en']->getFirstname(), 'new FirstName');
        $this->assertEquals($result['en']->getLastname(), 'new LastName');
    }

    public function testSetTranslations()
    {
        $translationFirstNameFr = new Translation();
        $translationFirstNameFr->setValue('Prénom')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationLastNameFr = new Translation();
        $translationLastNameFr->setValue('Nom')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('fr');
        $translationFirstNameEn = new Translation();
        $translationFirstNameEn->setValue('FirstName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("firstname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('en');
        $translationLastNameEn = new Translation();
        $translationLastNameEn->setValue('LastName')
            ->setEntityId($this->testObjectAnnotation->getId())
            ->setKey("lastname")
            ->setEntityName($this->testObjectAnnotation->getEntityName())
            ->setLang('en');
        $this->translationService->expects($this->exactly(4))
            ->method('edit')
            ->willReturnOnConsecutiveCalls(
                $this->returnValue($translationFirstNameFr),
                $this->returnValue($translationLastNameFr),
                $this->returnValue($translationFirstNameEn),
                $this->returnValue($translationLastNameEn)
            );
        $this->translationService->expects($this->once())->method('flush');
        $translator = new Translator(
            $this->translationService,
            $this->annotationClassLoader,
            $this->attributeClassLoader,
            $this->langs,
            Translator::METHOD_ANNOTATION,
            $this->logger
        );
        $translator->setResource(get_class($this->testObjectAnnotation));
        $this->assertTrue($translator instanceof Translator);
        $translationGroups = $translator->setTranslations(
            $this->testObjectAnnotation,
            [
                'fr' => ['firstname' => 'Prénom', 'lastname' => 'Nom'],
                'en' => ['firstname' => 'FirstName', 'lastname' => 'LastName'],
                'de' => ['firstname' => 'azerty', 'lastname' => 'azerty'],
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
