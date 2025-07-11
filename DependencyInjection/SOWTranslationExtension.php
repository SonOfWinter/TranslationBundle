<?php

namespace SOW\TranslationBundle\DependencyInjection;

use SOW\TranslationBundle\Service\TranslationServiceInterface;
use SOW\TranslationBundle\TranslatorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class SOWTranslationExtension
 *
 * @package SOW\TranslationBundle\DependencyInjection
 */
class SOWTranslationExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $this->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
        $container->setAlias(
            TranslatorInterface::class,
            new Alias('sow_translation.translator')
        );
        $container->setAlias(
            TranslationServiceInterface::class,
            new Alias('sow_translation.translation_service')
        );
    }
}
