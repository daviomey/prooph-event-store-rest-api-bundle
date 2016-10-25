<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\Formatter;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\JsonStreamEventFormatter;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\JsonStreamFormatter;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Pagination\StreamPaginator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamUriGenerator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\UnitTest;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventStore\Stream\Stream;
use Prooph\EventStore\Stream\StreamName;

class JsonStreamFormatterTest extends UnitTest
{

    /**
     * @var StreamUriGenerator | \PHPUnit_Framework_MockObject_MockObject
     */
    private $uriGenerator;

    /**
     * @var StreamPaginator | \PHPUnit_Framework_MockObject_MockObject
     */
    private $paginator;

    /**
     * @var JsonStreamEventFormatter | \PHPUnit_Framework_MockObject_MockObject
     */
    private $jsonEventFormatter;

    /**
     * @var JsonStreamFormatter
     */
    private $SUT;

    public function setUp()
    {
        parent::setUp();

        $this->uriGenerator = $this->mock(StreamUriGenerator::class);
        $this->paginator = $this->mock(StreamPaginator::class);
        $this->jsonEventFormatter = $this->mock(JsonStreamEventFormatter::class);
        $this->SUT = new JsonStreamFormatter($this->uriGenerator, $this->paginator, $this->jsonEventFormatter);
    }

    /**
     * @test
     */
    public function it_should_format_the_stream_to_json()
    {
        $streamName = $this->faker->word;
        $events = [
            $this->generateEvent(),
            $this->generateEvent()
        ];
        $stream = new Stream(new StreamName($streamName), new \ArrayIterator($events));
        $this->uriGenerator
            ->method('get')
            ->with($streamName)
            ->willReturn($this->generateStreamUri($stream));
        $this->paginator
            ->method('next')
            ->with($stream)
            ->willReturn('');
        $this->jsonEventFormatter
            ->method('format')
            ->withConsecutive(
                [$streamName, $events[0]],
                [$streamName, $events[1]]
            )
            ->willReturnOnConsecutiveCalls(
                $this->eventToJson($events[0]),
                $this->eventToJson($events[1])
            );

        $json = $this->streamToJson($stream);
        $this->assertJsonStringEqualsJsonString($json, $this->SUT->format($stream));
    }

    /**
     * @param Stream $stream
     *
     * @return string
     */
    private function streamToJson(Stream $stream)
    {
        $streamName = (string) $stream->streamName();
        $entries = [];
        foreach ($stream->streamEvents() as $event) {
            $entries[] = json_decode($this->eventToJson($event), true);
        }

        return json_encode([
            'id' => $this->generateStreamUri($stream),
            'title' => $streamName . ' stream',
            'updated' => null,
            'links' => [
                [
                    'uri' => '',
                    'relation' => 'next'
                ]
            ],
            'entries' => $entries
        ]);
    }

    /**
     * @param DomainEvent $event
     *
     * @return string
     */
    private function eventToJson(DomainEvent $event)
    {
        return json_encode([
            'id' => (string) $event->uuid()
        ]);
    }

    /**
     * @return DomainEvent
     */
    private function generateEvent()
    {
        $data = [
            'uuid' => $this->faker->uuid,
            'message_name' => $this->faker->sentence,
            'version' => $this->faker->numberBetween(),
            'metadata' => [],
            'created_at' => new \DateTimeImmutable(),
            'payload' => []
        ];

        return OrderPlaced::fromArray($data);
    }

    /**
     * @param Stream $stream
     * @param int $minVersion
     *
     * @return string
     */
    private function generateStreamUri(Stream $stream, $minVersion = 0)
    {
        return 'https://site.com/streams/' . $stream->streamName() . '/' . $minVersion;
    }

}

class OrderPlaced extends DomainEvent
{

    public function payload()
    {
        return [];
    }

    protected function setPayload(array $payload)
    {

    }

}