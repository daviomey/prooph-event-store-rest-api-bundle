<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Pagination;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamUriGenerator;
use Prooph\Common\Messaging\Message;
use Prooph\EventStore\Stream\Stream;

class StreamPaginator
{

    /**
     * @var StreamUriGenerator
     */
    private $uriGenerator;
    
    public function __construct(StreamUriGenerator $uriGenerator)
    {
        $this->uriGenerator = $uriGenerator;
    }

    /**
     * @param Stream $stream
     *
     * @return string URI of the next page
     */
    public function next(Stream $stream)
    {
        $streamName = (string) $stream->streamName();
        $nextEventVersion = $this->determineNextEventVersion($stream->streamEvents());

        return $this->uriGenerator->get($streamName, $nextEventVersion);
    }

    /**
     * @param \Iterator $events
     *
     * @return int
     */
    private function determineNextEventVersion(\Iterator $events)
    {
        $lastEvent = $this->getLastEvent($events);
        if ($lastEvent === null) {
            return 0;
        }

        return $lastEvent->version() + 1;
    }

    /**
     * @param \Iterator $events
     *
     * @return Message|null
     */
    private function getLastEvent(\Iterator $events)
    {
        $lastEvent = null;
        foreach ($events as $event) {
            $lastEvent = $event;
        }

        return $lastEvent;
    }

}