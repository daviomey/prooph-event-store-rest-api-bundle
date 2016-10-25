<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamEventUriGenerator;
use Prooph\Common\Messaging\DomainEvent;

class JsonStreamEventFormatter implements StreamEventFormatter
{

    /**
     * @var StreamEventUriGenerator
     */
    private $uriGenerator;

    public function __construct(StreamEventUriGenerator $uriGenerator)
    {
        $this->uriGenerator = $uriGenerator;
    }

    public function getOutputContentType()
    {
        return 'application/json';
    }

    public function format($streamName, DomainEvent $event)
    {
        return json_encode([
            'id' => $this->uriGenerator->get($streamName, $event),
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

}