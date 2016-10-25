<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Integration\Fixture;

use DateTimeInterface;
use Iterator;
use Prooph\EventStore\Adapter\Adapter;
use Prooph\EventStore\Exception\ConcurrencyException;
use Prooph\EventStore\Exception\StreamNotFoundException;
use Prooph\EventStore\Stream\Stream;
use Prooph\EventStore\Stream\StreamName;

class InMemoryAdapter implements Adapter
{

    /**
     * @param Stream $stream
     * @return void
     */
    public function create(Stream $stream)
    {
        // TODO: Implement create() method.
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
        // TODO: Implement appendTo() method.
    }

    /**
     * @param StreamName $streamName
     * @param null|int $minVersion Minimum version an event should have
     * @return Stream|null
     */
    public function load(StreamName $streamName, $minVersion = null)
    {
        return new Stream($streamName, new \ArrayIterator());
    }

    /**
     * @param StreamName $streamName
     * @param array $metadata If empty array is provided, then all events should be returned
     * @param null|int $minVersion Minimum version an event should have
     * @return Iterator
     */
    public function loadEvents(StreamName $streamName, array $metadata = [], $minVersion = null)
    {
        // TODO: Implement loadEvents() method.
    }

    /**
     * @param StreamName $streamName
     * @param DateTimeInterface|null $since
     * @param array $metadata
     * @return Iterator
     */
    public function replay(StreamName $streamName, DateTimeInterface $since = null, array $metadata = [])
    {
        // TODO: Implement replay() method.
    }

}