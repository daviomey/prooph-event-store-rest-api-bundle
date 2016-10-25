<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ProophEventStoreRestApiExtension extends Extension
{

    public function load(array $config, ContainerBuilder $container)
    {
        $this->loadServices($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function loadServices(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

}