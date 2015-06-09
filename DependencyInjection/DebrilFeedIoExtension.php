<?php

namespace Debril\FeedIoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DebrilFeedIoExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->setDefinition($container, 'logger', 'Psr\Log\NullLogger');
        $this->setDefinition($container, 'feedio.storage', 'Debril\FeedIoBundle\Adapter\MockStorage');
        
        $this->loadConfiguration($configs, $container);
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param array $configs
     * @param  ContainerBuilder $container
     */
    protected function loadConfiguration(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('feedio.private_feeds', $config['private']);
        
        return $this;
    }

    /**
     * @param ContainerBuilder $container
     * @param $serviceName
     * @param $className
     * @return $this
     */
    protected function setDefinition(ContainerBuilder $container, $serviceName, $className)
    {
        if ( ! $container->hasDefinition($serviceName) && ! $container->hasAlias($serviceName)) {
            $container->setDefinition($serviceName, new Definition($className));
        }

        return $this;
    }

}
