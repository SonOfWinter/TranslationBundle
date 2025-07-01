<?php

namespace SOW\TranslationBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SOW\TranslationBundle\Entity\AbstractTranslation;
use SOW\TranslationBundle\Entity\Translation;

/**
 * Class TranslationTest
 *
 * @package SOW\TranslationBundle\Tests\Entity
 */
class AbstractTranslationTest extends TestCase
{
    public function testSetterAndGetterTranslation(): void
    {
        $translation = new Translation();
        $translation->setValue('some name')
            ->setEntityId('1')
            ->setKey('name')
            ->setEntityName('someEntity')
            ->setLang('fr');
        $this->assertTrue($translation instanceof AbstractTranslation);
        $this->assertEquals('some name', $translation->getValue());
        $this->assertEquals('1', $translation->getEntityId());
        $this->assertEquals('name', $translation->getKey());
        $this->assertEquals('someEntity', $translation->getEntityName());
        $this->assertEquals('fr', $translation->getLang());
    }
}
