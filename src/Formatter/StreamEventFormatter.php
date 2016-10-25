<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter;

use Prooph\Common\Messaging\DomainEvent;

interface StreamEventFormatter extends Formatter
{

    /**
     * @param string $streamName
     * @param DomainEvent $event
     *
     * @return string
     */
    public function format($streamName, DomainEvent $event);

}