<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\Routing;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamRouteName;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamUriGenerator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\UnitTest;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class StreamUriGeneratorTest extends UnitTest
{

    /**
     * @var Router | \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * @var StreamUriGenerator
     */
    private $SUT;

    public function setUp()
    {
        parent::setUp();
        
        $this->router = $this->mock(Router::class);
        $this->SUT = new StreamUriGenerator($this->router);
    }

    /**
     * @test
     */
    public function it_should_generate_the_uri_for_getting_a_stream()
    {
        $streamName = $this->faker->word;
        $minVersion = $this->faker->numberBetween();
        $routeParams = [
            'name' => $streamName,
            'minVersion' => $minVersion
        ];
        $url = $this->faker->url;
        $this->router
            ->method('generate')
            ->with(StreamRouteName::GET, $routeParams)
            ->willReturn($url);

        $this->assertEquals($url, $this->SUT->get($streamName, $minVersion));
    }

}