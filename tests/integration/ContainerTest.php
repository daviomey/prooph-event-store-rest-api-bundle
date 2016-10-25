<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Integration;

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

}