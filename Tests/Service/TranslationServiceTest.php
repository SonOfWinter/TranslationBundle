<?php

namespace SOW\TranslationBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use SOW\TranslationBundle\Entity\AbstractTranslation;
use SOW\TranslationBundle\Entity\Translation;
use SOW\TranslationBundle\Repository\TranslationRepository;
use SOW\TranslationBundle\Service\TranslationService;
use SOW\TranslationBundle\Tests\Fixtures\AttributedClasses\TestObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TranslationServiceTest
 *
 * @package SOW\TranslationBundle\Tests\Service
 */
class TranslationServiceTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface | MockObject
     */
    protected $em;

    /**
     * @var TranslationRepository | MockObject
     */
    protected $repository;

    /**
     * @var TestObject
     */
    protected $testObject;

    /**
     * @var AbstractTranslation | MockObject
     */
    protected $translation;

    /** @var string[] */
    private array $langs = ['fr', 'en'];

    public function setUp(): void
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

    public function testFindAllForObjectWithLang(): void
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findAllForObjectWithLang($this->testObject, 'fr');
        $this->assertEquals($result, [$this->translation]);
    }

    public function testFindAllForObjectWithWrongLang(): void
    {
        static::expectException('SOW\TranslationBundle\Exception\TranslatorLangException');
        static::expectExceptionMessage('Lang not in language list');
        $this->repository->expects($this->never())
            ->method('findBy');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $service->findAllForObjectWithLang($this->testObject, 'ru');
    }

    public function testFindOneForObjectWithLang(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->translation));
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findOneForObjectWithLang($this->testObject, 'name', 'fr');
        $this->assertEquals($result, $this->translation);
    }

    public function testFindOneForObjectWithWrongLang(): void
    {
        static::expectException('SOW\TranslationBundle\Exception\TranslatorLangException');
        static::expectExceptionMessage('Lang not in language list');
        $this->repository->expects($this->never())
            ->method('findOneBy');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $service->findOneForObjectWithLang($this->testObject, 'name', 'ru');
    }

    public function testFindAllForObject(): void
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findAllForObject($this->testObject);
        $this->assertEquals($result, [$this->translation]);
    }

    public function testfindByKey(): void
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findByKey('name');
        $this->assertEquals($result, [$this->translation]);
    }

    public function testfindByEntityNameAndLang(): void
    {
        $this->repository->expects($this->once())
            ->method('findAllByEntityNameAndLang')
            ->will($this->returnValue([$this->translation]));
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->findByEntityNameAndLang(
            'TestObject',
            [$this->testObject->getId()],
            'fr'
        );
        $this->assertEquals($result, [$this->translation]);
    }

    public function testCheckTranslationWithTranslation(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->translation));
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->checkTranslation($this->testObject, 'name', 'fr');
        $this->assertTrue($result);
    }

    public function testCheckTranslationWithoutTranslation(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->checkTranslation($this->testObject, 'name', 'fr');
        $this->assertFalse($result);
    }

    public function testCreateWithoutFlush(): void
    {
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
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

    public function testCreateWithFlush(): void
    {
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
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

    public function testEditWithFoundObjectWithFlush(): void
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
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
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

    public function testEditWithMissingObjectWithFlush(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
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

    public function testRemoveWithFlush(): void
    {
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->remove($this->translation, true);
        $this->assertTrue($result);
    }

    public function testRemoveByObjectKeyAndLangWithFlush(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->translation));
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->removeByObjectKeyAndLang($this->testObject, 'name', 'fr', true);
        $this->assertTrue($result);
    }

    public function testRemoveByObjectKeyAndLangButNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));
        $this->em->expects($this->never())->method('remove');
        $this->em->expects($this->never())->method('flush');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->removeByObjectKeyAndLang($this->testObject, 'name', 'fr', true);
        $this->assertTrue($result);
    }

    public function testRemoveAllForTranslatableWithFlush(): void
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->removeAllForTranslatable($this->testObject, true);
        $this->assertTrue($result);
    }

    public function testremoveAllByKeyWithFlush(): void
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([$this->translation]));
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $service = new TranslationService(
            $this->em,
            $this->repository,
            Translation::class,
            $this->langs
        );
        $this->assertTrue($service instanceof TranslationService);
        $result = $service->removeAllByKey('name', true);
        $this->assertTrue($result);
    }
}
