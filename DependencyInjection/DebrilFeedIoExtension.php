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
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $default = array(
            \DateTime::RFC3339,
            \DateTime::RSS,
            \DateTime::W3C,
            'Y-m-d\TH:i:s.uP',
            'Y-m-d',
            'd/m/Y'
        );

        if (!isset($config['date_formats'])) {
            $container->setParameter(
                    'debril_rss_atom.date_formats', $default
            );
        } else {
            $container->setParameter(
                    'debril_rss_atom.date_formats', array_merge($default, $config['date_formats'])
            );
        }
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
