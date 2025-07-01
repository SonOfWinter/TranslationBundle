<?php

namespace SOW\TranslationBundle\Tests\DependencyInjection;

use SOW\TranslationBundle\DependencyInjection\Configuration;
use SOW\TranslationBundle\DependencyInjection\SOWTranslationExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class SOWTranslationExtensionTest
 *
 * @package SOW\BindingBundle\Tests\DependencyInjection
 */
class SOWTranslationExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())
            ->method('getReflectionClass')
            ->will($this->returnValue(new \ReflectionClass(Configuration::class)));
        $container->expects($this->exactly(2))
            ->method('setAlias');
        $extension = new SOWTranslationExtension();
        $extension->load([], $container);
    }
}
