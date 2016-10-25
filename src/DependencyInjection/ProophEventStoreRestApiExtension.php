<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\DependencyInjection;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Controller\StreamEventsController;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Controller\StreamsController;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class ProophEventStoreRestApiExtension extends ConfigurableExtension
{

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $this->loadServices($container);
        $this->setControllerDefinitions($mergedConfig, $container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function loadServices(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param array $mergedConfig
     * @param ContainerBuilder $container
     */
    private function setControllerDefinitions(array $mergedConfig, ContainerBuilder $container)
    {
        $eventStoreName = $mergedConfig['event_store']['name'];
        $eventStoreServiceId = 'prooph_event_store.' . $eventStoreName;
        $formatters = $mergedConfig['formatters'];

        $this->setStreamEventsControllerDefinition($formatters['event'], $eventStoreServiceId, $container);
        $this->setStreamsControllerDefinition($formatters['stream'], $eventStoreServiceId, $container);
    }

    /**
     * @param string $formatterServiceId
     * @param string $eventStoreServiceId
     * @param ContainerBuilder $container
     */
    private function setStreamEventsControllerDefinition($formatterServiceId, $eventStoreServiceId, ContainerBuilder $container)
    {
        $def = new Definition(StreamEventsController::class);
        $def
            ->addArgument(new Reference($formatterServiceId))
            ->addArgument(new Reference($eventStoreServiceId));

        $container->setDefinition('prooph_event_store_rest_api.stream_events_controller', $def);
    }

    /**
     * @param string $formatterServiceId
     * @param string $eventStoreServiceId
     * @param ContainerBuilder $container
     */
    private function setStreamsControllerDefinition($formatterServiceId, $eventStoreServiceId, ContainerBuilder $container)
    {
        $def = new Definition(StreamsController::class);
        $def
            ->addArgument(new Reference($formatterServiceId))
            ->addArgument(new Reference($eventStoreServiceId));

        $container->setDefinition('prooph_event_store_rest_api.streams_controller', $def);
    }

}