<?php

namespace SOW\TranslationBundle\Tests\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use SOW\TranslationBundle\Entity\AbstractTranslation;
use SOW\TranslationBundle\Repository\TranslationRepository;
use SOW\TranslationBundle\Tests\Fixtures\AttributedClasses\TestObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TranslationServiceTest
 *
 * @package SOW\TranslationBundle\Tests\Service
 */
class TranslationRepositoryTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface | MockObject
     */
    protected $em;

    /**
     * @var AbstractTranslation | MockObject
     */
    protected $translation;

    /**
     * @var AbstractTranslation | MockObject
     */
    protected $translation2;

    public function setUp(): void
    {
        $this->em = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->translation = $this->getMockBuilder(AbstractTranslation::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->translation2 = $this->getMockBuilder(AbstractTranslation::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
    /*
        public function testFindOneBy(): void
        {
            $comparison = $this->getMockBuilder(Expr\Comparison::class)
                ->disableOriginalConstructor()
                ->getMock();
            $queryExpr = $this->getMockBuilder(Expr::class)
                ->disableOriginalConstructor()
                ->getMock();
            $query = $this->getMockBuilder(AbstractQuery::class)
                ->disableOriginalConstructor()
                ->getMock();
            $query->expects($this->once())
                ->method('getOneOrNullResult')
                ->will($this->returnValue($this->translation));
            $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
                ->disableOriginalConstructor()
                ->getMock();
            $queryBuilder->expects($this->once())
                ->method('select')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->once())
                ->method('from')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->exactly(2))
                ->method('andWhere')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->exactly(2))
                ->method('setParameter')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->once())
                ->method('getQuery')
                ->will($this->returnValue($query));
            $queryExpr->expects($this->exactly(2))
                ->method('eq')
                ->will($this->returnValue($comparison));
            $queryBuilder->expects($this->exactly(2))
                ->method('expr')
                ->will($this->returnValue($queryExpr));
            $this->em->expects($this->once())
                ->method('createQueryBuilder')
                ->will($this->returnValue($queryBuilder));
            $repository = new TranslationRepository($this->em, AbstractTranslation::class);
            $this->assertTrue($repository instanceof TranslationRepository);
            $result = $repository->findOneBy(["lang" => "fr", "entityName" => TestObject::class]);
            $this->assertEquals($result, $this->translation);
        }


        public function testFindBy(): void
        {
            $comparison = $this->getMockBuilder(Expr\Comparison::class)
                ->disableOriginalConstructor()
                ->getMock();
            $queryExpr = $this->getMockBuilder(Expr::class)
                ->disableOriginalConstructor()
                ->getMock();
            $query = $this->getMockBuilder(AbstractQuery::class)
                ->disableOriginalConstructor()
                ->getMock();
            $query->expects($this->once())
                ->method('getResult')
                ->will($this->returnValue([$this->translation]));
            $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
                ->disableOriginalConstructor()
                ->getMock();
            $queryBuilder->expects($this->once())
                ->method('select')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->once())
                ->method('from')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->exactly(2))
                ->method('andWhere')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->exactly(2))
                ->method('setParameter')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->once())
                ->method('orderBy')
                ->will($this->returnValue($queryBuilder));
            $queryBuilder->expects($this->once())
                ->method('getQuery')
                ->will($this->returnValue($query));
            $queryExpr->expects($this->exactly(2))
                ->method('eq')
                ->will($this->returnValue($comparison));
            $queryBuilder->expects($this->exactly(2))
                ->method('expr')
                ->will($this->returnValue($queryExpr));
            $this->em->expects($this->once())
                ->method('createQueryBuilder')
                ->will($this->returnValue($queryBuilder));
            $repository = new TranslationRepository($this->em, AbstractTranslation::class);
            $this->assertTrue($repository instanceof TranslationRepository);
            $result = $repository->findBy(["lang" => "fr", "entityName" => TestObject::class], ['lang' => 'fr']);
            $this->assertEquals($result, [$this->translation]);
        }
    */
    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @return void
     */
    public function testFindAllByObjectAndLangs(): void
    {
        $query = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue([$this->translation]));
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $queryBuilder->expects($this->once())
            ->method('select')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('from')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('where')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->exactly(2))
            ->method('andWhere')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('orderBy')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('setParameters')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->will($this->returnValue($query));
        $this->em->expects($this->once())
            ->method('createQueryBuilder')
            ->will($this->returnValue($queryBuilder));
        $testObject = new TestObject();
        $repository = new TranslationRepository($this->em, AbstractTranslation::class);
        $result = $repository->findAllByObjectAndLangs($testObject, ['fr']);
        $this->assertEquals($result, [$this->translation]);
    }

    public function testFindAllByEntityNameAndLang(): void
    {
        $translations = [$this->translation, $this->translation2];
        $query = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue($translations));
        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $queryBuilder->expects($this->once())
            ->method('select')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('from')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('where')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->exactly(2))
            ->method('andWhere')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('orderBy')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('setParameters')
            ->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->will($this->returnValue($query));
        $this->em->expects($this->once())
            ->method('createQueryBuilder')
            ->will($this->returnValue($queryBuilder));
        $repository = new TranslationRepository($this->em, AbstractTranslation::class);
        $this->assertTrue($repository instanceof TranslationRepository);
        $result = $repository->findAllByEntityNameAndLang('TestObject', ['1'], 'fr');
        $this->assertEquals($translations, $result);
    }
}
