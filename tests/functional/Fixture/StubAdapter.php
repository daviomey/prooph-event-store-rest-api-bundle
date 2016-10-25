<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Functional\Fixture;

use DateTimeInterface;
use Faker\Factory;
use Iterator;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventStore\Adapter\Adapter;
use Prooph\EventStore\Exception\ConcurrencyException;
use Prooph\EventStore\Exception\StreamNotFoundException;
use Prooph\EventStore\Stream\Stream;
use Prooph\EventStore\Stream\StreamName;

class StubAdapter implements Adapter
{

    /**
     * @param Stream $stream
     * @return void
     */
    public function create(Stream $stream)
    {

    }

    /**
     * @param StreamName $streamName
     * @param Iterator $domainEvents
     * @throws StreamNotFoundException If stream does not exist
     * @throws ConcurrencyException If two processes are trying to append to the same stream at the same time
     * @return void
     */
    public function appendTo(StreamName $streamName, Iterator $domainEvents)
    {

    }

    /**
     * @param StreamName $streamName
     * @param null|int $minVersion Minimum version an event should have
     * @return Stream|null
     */
    public function load(StreamName $streamName, $minVersion = null)
    {
        $events = [$this->generateEvent()];
        return new Stream($streamName, new \ArrayIterator($events));
    }

    /**
     * @param StreamName $streamName
     * @param array $metadata If empty array is provided, then all events should be returned
     * @param null|int $minVersion Minimum version an event should have
     * @return Iterator
     */
    public function loadEvents(StreamName $streamName, array $metadata = [], $minVersion = null)
    {

    }

    /**
     * @param StreamName $streamName
     * @param DateTimeInterface|null $since
     * @param array $metadata
     * @return Iterator
     */
    public function replay(StreamName $streamName, DateTimeInterface $since = null, array $metadata = [])
    {

    }

    /**
     * @return DomainEvent
     */
    private function generateEvent()
    {
        $faker = Factory::create();

        return OrderPlaced::fromArray([
            'uuid' => $faker->uuid,
            'message_name' => $faker->sentence,
            'version' => $faker->numberBetween(),
            'metadata' => [],
            'created_at' => new \DateTimeImmutable(),
            'payload' => []
        ]);
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