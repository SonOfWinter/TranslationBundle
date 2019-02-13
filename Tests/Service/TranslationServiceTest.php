<?php

/**
 * TranslationServiceTest
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests\Service
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use SOW\TranslationBundle\Entity\AbstractTranslation;
use SOW\TranslationBundle\Entity\Translation;
use SOW\TranslationBundle\Repository\TranslationRepository;
use SOW\TranslationBundle\Service\TranslationService;
use SOW\TranslationBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TranslationServiceTest
 *
 * @package SOW\TranslationBundle\Tests\Service
 */
class TranslationServiceTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TranslationRepository
     */
    protected $repository;

    /**
     * @var TestObject
     */
    protected $testObject;

    /**
     * @var AbstractTranslation
     */
    protected $translation;
    
    private $langs = ['fr', 'en'];

    public function setUp()
    {
        $this->em = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->repository = $this->getMockBuilder(TranslationRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->translation = $this->getMockBuilder(Translation::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->testObject = new TestObject();
    }

    public function testFindAllForObjectWithLang()
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findAllForObjectWithLang($this->testObject, 'fr');
        $this->assertEquals($result, [$this->translation]);
    }

    /**
     * @expectedException SOW\TranslationBundle\Exception\TranslatorLangException
     * @expectedExceptionMessage Lang not in language list
     */
    public function testFindAllForObjectWithWrongLang()
    {
        $this->repository->expects($this->never())
            ->method('findBy');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $service->findAllForObjectWithLang($this->testObject, 'ru');
    }

    public function testFindOneForObjectWithLang()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->translation));
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findOneForObjectWithLang($this->testObject, 'name', 'fr');
        $this->assertEquals($result, $this->translation);
    }

    /**
     * @expectedException SOW\TranslationBundle\Exception\TranslatorLangException
     * @expectedExceptionMessage Lang not in language list
     */
    public function testFindOneForObjectWithWrongLang()
    {
        $this->repository->expects($this->never())
            ->method('findOneBy');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $service->findOneForObjectWithLang($this->testObject, 'name', 'ru');
    }

    public function testFindAllForObject()
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findAllForObject($this->testObject);
        $this->assertEquals($result, [$this->translation]);
    }

    public function testfindByKey()
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findByKey('name');
        $this->assertEquals($result, [$this->translation]);
    }

    public function testCheckTranslationWithTranslation()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->translation));
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->checkTranslation($this->testObject, 'name', 'fr');
        $this->assertTrue($result);
    }

    public function testCheckTranslationWithoutTranslation()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->checkTranslation($this->testObject, 'name', 'fr');
        $this->assertFalse($result);
    }

    public function testCreateWithoutFlush()
    {
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $translation = $service->create(
            $this->testObject,
            'fr',
            'name',
            'test'
        );
        $this->assertTrue($translation instanceof AbstractTranslation);
        $this->assertEquals($translation->getLang(), 'fr');
        $this->assertEquals($translation->getEntityName(), $this->testObject->getEntityName());
        $this->assertEquals($translation->getEntityId(), $this->testObject->getId());
        $this->assertEquals($translation->getKey(), 'name');
        $this->assertEquals($translation->getValue(), 'test');
    }

    public function testCreateWithFlush()
    {
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $translation = $service->create(
            $this->testObject,
            'fr',
            'name',
            'test',
            true
        );
        $this->assertTrue($translation instanceof AbstractTranslation);
        $this->assertEquals($translation->getLang(), 'fr');
        $this->assertEquals($translation->getEntityName(), $this->testObject->getEntityName());
        $this->assertEquals($translation->getEntityId(), $this->testObject->getId());
        $this->assertEquals($translation->getKey(), 'name');
        $this->assertEquals($translation->getValue(), 'test');
    }

    public function testEditWithFoundObjectWithFlush()
    {
        $translation = new Translation();
        $translation->setEntityId($this->testObject->getId())
            ->setEntityName($this->testObject->getEntityName())
            ->setKey('name')
            ->setLang('fr')
            ->setValue('testest');
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($translation));
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $translation = $service->edit(
            $this->testObject,
            'fr',
            'name',
            'test',
            true
        );
        $this->assertTrue($translation instanceof AbstractTranslation);
        $this->assertEquals($translation->getLang(), 'fr');
        $this->assertEquals($translation->getEntityName(), $this->testObject->getEntityName());
        $this->assertEquals($translation->getEntityId(), $this->testObject->getId());
        $this->assertEquals($translation->getKey(), 'name');
        $this->assertEquals($translation->getValue(), 'test');
    }

    public function testEditWithMissingObjectWithFlush()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $translation = $service->edit(
            $this->testObject,
            'fr',
            'name',
            'test',
            true
        );
        $this->assertTrue($translation instanceof AbstractTranslation);
        $this->assertEquals($translation->getEntityName(), $this->testObject->getEntityName());
        $this->assertEquals($translation->getEntityId(), $this->testObject->getId());
        $this->assertEquals($translation->getValue(), 'test');
    }

    public function testRemoveWithFlush()
    {
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->remove($this->translation, true);
        $this->assertTrue($result);
    }

    public function testRemoveByObjectKeyAndLangWithFlush()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->translation));
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->removeByObjectKeyAndLang($this->testObject, 'name', 'fr', true);
        $this->assertTrue($result);
    }

    public function testRemoveByObjectKeyAndLangButNotFound()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));
        $this->em->expects($this->never())->method('remove');
        $this->em->expects($this->never())->method('flush');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->removeByObjectKeyAndLang($this->testObject, 'name', 'fr', true);
        $this->assertTrue($result);
    }

    public function testRemoveAllForTranslatableWithFlush()
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->removeAllForTranslatable($this->testObject, true);
        $this->assertTrue($result);
    }

    public function testremoveAllByKeyWithFlush()
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, $this->repository, Translation::class, $this->langs);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->removeAllByKey('name', true);
        $this->assertTrue($result);
    }
}
