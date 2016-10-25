<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\Pagination;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Pagination\StreamPaginator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamUriGenerator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\UnitTest;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventStore\Stream\Stream;
use Prooph\EventStore\Stream\StreamName;

class StreamPaginatorTest extends UnitTest
{

    /**
     * @var StreamUriGenerator | \PHPUnit_Framework_MockObject_MockObject
     */
    private $uriGenerator;

    /**
     * @var StreamPaginator
     */
    private $SUT;

    public function setUp()
    {
        parent::setUp();

        $this->uriGenerator = $this->mock(StreamUriGenerator::class);
        $this->SUT = new StreamPaginator($this->uriGenerator);
    }

    /**
     * @test
     */
    public function it_should_return_the_next_stream_page_uri_when_the_stream_has_no_events()
    {
        $streamName = $this->faker->word;
        $stream = new Stream(new StreamName($streamName), new \ArrayIterator());
        $uri = $this->faker->url;
        $this->uriGenerator
            ->method('get')
            ->with($streamName, 0)
            ->willReturn($uri);

        $this->assertEquals($uri, $this->SUT->next($stream));
    }

    /**
     * @test
     */
    public function it_should_return_the_next_stream_page_uri_when_the_stream_has_multiple_events()
    {
        $streamName = $this->faker->word;
        $events = [
            $this->generateEvent(56),
            $this->generateEvent(57),
            $this->generateEvent(58),
        ];
        $stream = new Stream(new StreamName($streamName), new \ArrayIterator($events));
        $uri = $this->faker->url;
        $nextEventVersion = $events[2]->version() + 1;
        $this->uriGenerator
            ->method('get')
            ->with($streamName, $nextEventVersion)
            ->willReturn($uri);

        $this->assertEquals($uri, $this->SUT->next($stream));
    }

    /**
     * @param int $version
     *
     * @return OrderPlaced
     */
    private function generateEvent($version)
    {
        $data = [
            'uuid' => $this->faker->uuid,
            'message_name' => $this->faker->sentence,
            'version' => $version,
            'metadata' => [],
            'created_at' => new \DateTimeImmutable(),
            'payload' => []
        ];

        return OrderPlaced::fromArray($data);
    }

}

class OrderPlaced extends DomainEvent
{

    public function payload()
    {
        // TODO: Implement payload() method.
    }

    protected function setPayload(array $payload)
    {
        // TODO: Implement setPayload() method.
    }

}