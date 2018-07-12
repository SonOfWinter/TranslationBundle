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

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
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
class TranslationRepositoryTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var Translation
     */
    protected $translation;

    public function setUp()
    {
        $this->em = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->translation = $this->getMockBuilder(Translation::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testFindOneBy()
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
        $repository = new TranslationRepository($this->em, Translation::class);
        $this->assertTrue($repository instanceof TranslationRepository);
        $result = $repository->findOneBy(["lang" => "fr", "entityName" => TestObject::class]);
        $this->assertEquals($result, $this->translation);
    }
}
