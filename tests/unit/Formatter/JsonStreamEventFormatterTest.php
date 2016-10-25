<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\Formatter;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\JsonStreamEventFormatter;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamEventUriGenerator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit\UnitTest;
use Prooph\Common\Messaging\DomainEvent;

class JsonStreamEventFormatterTest extends UnitTest
{

    /**
     * @var StreamEventUriGenerator | \PHPUnit_Framework_MockObject_MockObject
     */
    private $uriGenerator;
    
    /**
     * @var JsonStreamEventFormatter
     */
    private $SUT;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->uriGenerator = $this->mock(StreamEventUriGenerator::class);
        $this->SUT = new JsonStreamEventFormatter($this->uriGenerator);
    }
    
    /**
     * @test
     */
    public function it_should_format_the_stream_event_to_json()
    {
        $streamName = $this->faker->word;
        $data = [
            'uuid' => $this->faker->uuid,
            'message_name' => $this->faker->sentence,
            'version' => $this->faker->numberBetween(),
            'metadata' => [],
            'created_at' => new \DateTimeImmutable(),
            'payload' => []
        ];
        $event = StudentEnrolled::fromArray($data);
        $json = $this->toJson($streamName, $event);
        $this->uriGenerator
            ->method('get')
            ->with($streamName, $event)
            ->willReturn($this->generateStreamEventUri($streamName, $event));

        $this->assertJsonStringEqualsJsonString($json, $this->SUT->format($streamName, $event));
    }

    /**
     * @param string $streamName
     * @param DomainEvent $event
     *
     * @return string
     */
    private function toJson($streamName, DomainEvent $event)
    {
        return json_encode([
            'id' => $this->generateStreamEventUri($streamName, $event),
            'title' => $event->version() . '@' . $streamName,
            'updated' => $event->createdAt()->format('Y-m-d H:i:s'),
            'content' => [
                'id' => (string) $event->uuid(),
                'name' => $event->messageName(),
                'version' => $event->version(),
                'metadata' => $event->metadata(),
                'createdAt' => $event->createdAt()->format('Y-m-d H:i:s'),
                'payload' => $event->payload()
            ]
        ]);
    }

    /**
     * @param string $streamName
     * @param DomainEvent $event
     *
     * @return string
     */
    private function generateStreamEventUri($streamName, DomainEvent $event)
    {
        return 'https://site.com/api/streams/' . $streamName . '/events/' . $event->version();
    }
    
}

class StudentEnrolled extends DomainEvent
{

    /**
     * @var array
     */
    private $payload;

    public function payload()
    {
        return $this->payload;
    }

    protected function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

}