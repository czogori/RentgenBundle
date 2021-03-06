<?php
namespace Czogori\RentgenBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;

class ConnectionPass implements CompilerPassInterface
{
    /**
     * Processes container.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {

        $dir = __DIR__ . '/../../../../../app/config';
        $fileLocator = new FileLocator($dir);
        foreach(array('dev', 'prod', 'test') as $environment) {
            $configFile = $fileLocator->locate('config_' . $environment . '.yml');
            $config = Yaml::parse($configFile);
            if (isset($config['propel']['dbal'])) {
                $connectionConfig[$environment] = $config['propel']['dbal'];
                $connectionConfig[$environment]['adapter'] = $config['propel']['dbal']['driver'];
                $connectionConfig[$environment]['username'] = $config['propel']['dbal']['user'];
            }
        }
        if (isset($connectionConfig)) {
            $definition = $container->getDefinition('connection_config');
            $definition->setArguments(array($connectionConfig));
        }
    }
}
