<?php

namespace Zaeder\JsLoggerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZaederJsLoggerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $levels = array_map(function ($level) {
            return strtolower($level);
        }, $config['allowed_levels']);
        $container->setParameter('zaeder_js_logger.allowed_levels', $levels);
        $container->setParameter('zaeder_js_logger.ignore_messages', $config['ignore_messages']);
        $container->setParameter('zaeder_js_logger.ignore_url_prefixes', $config['ignore_url_prefixes']);
        $container->setParameter('zaeder_js_logger.monolog_channel', $config['monolog_channel']);

        /*$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');*/
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
