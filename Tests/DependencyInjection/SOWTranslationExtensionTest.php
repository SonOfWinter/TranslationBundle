<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  SOW\BindingBundle\Tests\DependencyInjection
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\TranslationBundle\Tests\DependencyInjection;

use SOW\TranslationBundle\DependencyInjection\Configuration;
use SOW\TranslationBundle\DependencyInjection\SOWTranslationExtension;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class SOWTranslationExtensionTest
 *
 * @package SOW\BindingBundle\Tests\DependencyInjection
 */
class SOWTranslationExtensionTest extends TestCase
{
    public function testLoad()
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