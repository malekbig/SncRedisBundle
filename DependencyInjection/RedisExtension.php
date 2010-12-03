<?php

namespace Bundle\RedisBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RedisExtension extends Extension
{
    /**
     * Loads the configuration.
     *
     * @param array $config An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function configLoad($config, ContainerBuilder $container)
    {
        if (!$container->hasDefinition('redis')) {
            $loader = new XmlFileLoader($container, __DIR__ . '/../Resources/config');
            $loader->load('redis.xml');
        }
        if (isset($config['servers'])) {
            $container->setParameter('redis.connection.servers', $config['servers']);
        }
    }

    /**
     * Loads the configuration.
     *
     * @param array $config An array of configuration settings
     * @param \Symfony\Components\DependencyInjection\ContainerBuilder $container A ContainerBuilder instance
     */
    public function sessionLoad($config, ContainerBuilder $container)
    {
        if (!$container->hasDefinition('session.storage.redis')) {
            $loader = new XmlFileLoader($container, __DIR__ . '/../Resources/config');
            $loader->load('session.xml');
        }
        
        foreach ($config AS $key => $value) {
            $container->setParameter('session.storage.redis.options.' . $key, $value);
        }
        
        $container->setAlias('session.storage', 'session.storage.redis');
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     */
    public function getNamespace()
    {
        return 'http://www.symfony-project.org/schema/dic/symfony';
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__ . '/../Resources/config/';
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'redis';
    }
}