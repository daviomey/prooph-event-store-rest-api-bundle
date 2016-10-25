<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('prooph_event_store_rest_api');

        $rootNode
            ->children()
                ->arrayNode('event_store')
                    ->children()
                        ->scalarNode('name')->end()
                    ->end()
                ->end()
                ->arrayNode('formatters')
                    ->children()
                        ->scalarNode('event')
                            ->defaultValue('prooph_event_store_rest_api.json_stream_event_formatter')
                        ->end()
                        ->scalarNode('stream')
                            ->defaultValue('prooph_event_store_rest_api.json_stream_formatter')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

}