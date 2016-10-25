<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Integration;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Controller\StreamEventsController;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Controller\StreamsController;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\JsonStreamEventFormatter;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\JsonStreamFormatter;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Pagination\StreamPaginator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamEventUriGenerator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamUriGenerator;

class ContainerTest extends IntegrationTest
{
    
    /**
     * @test
     */
    public function it_should_return_the_stream_event_uri_generator_service()
    {
        $service = $this->container->get('prooph_event_store_rest_api.stream_event_uri_generator');
        $this->assertInstanceOf(StreamEventUriGenerator::class, $service);
    }

    /**
     * @test
     */
    public function it_should_return_the_stream_uri_generator_service()
    {
        $service = $this->container->get('prooph_event_store_rest_api.stream_uri_generator');
        $this->assertInstanceOf(StreamUriGenerator::class, $service);
    }

    /**
     * @test
     */
    public function it_should_return_the_stream_paginator_service()
    {
        $service = $this->container->get('prooph_event_store_rest_api.stream_paginator');
        $this->assertInstanceOf(StreamPaginator::class, $service);
    }

    /**
     * @test
     */
    public function it_should_return_the_json_stream_event_formatter_service()
    {
        $service = $this->container->get('prooph_event_store_rest_api.json_stream_event_formatter');
        $this->assertInstanceOf(JsonStreamEventFormatter::class, $service);
    }

    /**
     * @test
     */
    public function it_should_return_the_json_stream_formatter_service()
    {
        $service = $this->container->get('prooph_event_store_rest_api.json_stream_formatter');
        $this->assertInstanceOf(JsonStreamFormatter::class, $service);
    }

    /**
     * @test
     */
    public function it_should_return_the_stream_events_controller_service()
    {
        $service = $this->container->get('prooph_event_store_rest_api.stream_events_controller');
        $this->assertInstanceOf(StreamEventsController::class, $service);
    }

    /**
     * @test
     */
    public function it_should_return_the_streams_controller_service()
    {
        $service = $this->container->get('prooph_event_store_rest_api.streams_controller');
        $this->assertInstanceOf(StreamsController::class, $service);
    }

}