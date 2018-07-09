<?php

/**
 * Configuration test
 *
 * PHP Version 7.1
 *
 * @package  SOW\TranslationBundle\Tests\DependencyInjection
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/TranslationBundle
 */

namespace SOW\TranslationBundle\Tests\DependencyInjection;

use SOW\TranslationBundle\DependencyInjection\Configuration;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class ConfigurationTest
 *
 * @package SOW\TranslationBundle\Tests\DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    public function testConfiguration()
    {
        $configuration = new Configuration();
        $tree = $configuration->getConfigTreeBuilder();
        $this->assertTrue($tree instanceof TreeBuilder);
    }
}
