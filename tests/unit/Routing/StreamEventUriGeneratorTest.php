<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\Routing;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamEventRouteName;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamEventUriGenerator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\UnitTest;
use Prooph\Common\Messaging\Message;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class StreamEventUriGeneratorTest extends UnitTest
{

    /**
     * @var Router | \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * @var StreamEventUriGenerator
     */
    private $SUT;

    public function setUp()
    {
        parent::setUp();

        $this->router = $this->mock(Router::class);
        $this->SUT = new StreamEventUriGenerator($this->router);
    }

    /**
     * @test
     */
    public function it_should_generate_the_uri_for_getting_a_stream_event()
    {
        $streamName = $this->faker->word;
        $version = $this->faker->numberBetween();
        $routeParams = [
            'streamName' => $streamName,
            'version' => $version
        ];
        $event = $this->mock(Message::class);
        $event
            ->method('version')
            ->willReturn($version);
        $routeName = StreamEventRouteName::GET;
        $url = $this->faker->url;
        $this->router
            ->method('generate')
            ->with($routeName, $routeParams)
            ->willReturn($url);

        $this->assertEquals($url, $this->SUT->get($streamName, $event));
    }

}