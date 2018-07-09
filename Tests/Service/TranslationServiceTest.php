<?php

/**
 * TranslationServiceTest
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests\Service
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\TranslationBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
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
     * @var Translation
     */
    protected $translation;

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
        $this->em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));
        $service = new TranslationService($this->em, Translation::class);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findAllForObjectWithLang($this->testObject, 'fr');
        $this->assertEquals($result, [$this->translation]);
    }

    public function testFindOneForObjectWithLang()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->translation));
        $this->em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));
        $service = new TranslationService($this->em, Translation::class);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findOneForObjectWithLang($this->testObject, 'name', 'fr');
        $this->assertEquals($result, $this->translation);
    }

    public function testFindAllForObject()
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $this->em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));
        $service = new TranslationService($this->em, Translation::class);
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findAllForObject($this->testObject);
        $this->assertEquals($result, [$this->translation]);
    }

    public function testCreateWithoutFlush()
    {
        $service = new TranslationService($this->em, Translation::class);
        $this->assertTrue($service instanceof TranslationService);
        $translation = $service->create(
            $this->testObject,
            'fr',
            'name',
            'test'
        );
        $this->assertTrue($translation instanceof Translation);
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
        $service = new TranslationService($this->em, Translation::class);
        $this->assertTrue($service instanceof TranslationService);
        $translation = $service->create(
            $this->testObject,
            'fr',
            'name',
            'test',
            true
        );
        $this->assertTrue($translation instanceof Translation);
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
        $this->em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, Translation::class);
        $this->assertTrue($service instanceof TranslationService);
        $translation = $service->edit(
            $this->testObject,
            'fr',
            'name',
            'test',
            true
        );
        $this->assertTrue($translation instanceof Translation);
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
        $this->em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService($this->em, Translation::class);
        $this->assertTrue($service instanceof TranslationService);
        $translation = $service->edit(
            $this->testObject,
            'fr',
            'name',
            'test',
            true
        );
        $this->assertTrue($translation instanceof Translation);
        $this->assertEquals($translation->getEntityName(), $this->testObject->getEntityName());
        $this->assertEquals($translation->getEntityId(), $this->testObject->getId());
        $this->assertEquals($translation->getValue(), 'test');
    }
}
