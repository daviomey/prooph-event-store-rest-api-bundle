<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Controller;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\StreamEventFormatter;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Exception\StreamNotFoundException;
use Prooph\EventStore\Stream\StreamName;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StreamEventsController
{

    /**
     * @var StreamEventFormatter
     */
    private $formatter;

    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(StreamEventFormatter $formatter, EventStore $eventStore)
    {
        $this->formatter = $formatter;
        $this->eventStore = $eventStore;
    }

    /**
     * @param Request $request
     * @param string $streamName
     * @param int $version
     *
     * @return Response
     */
    public function getAction(Request $request, $streamName, $version = 0)
    {
        $headers = ['Content-Type' => $this->formatter->getOutputContentType()];

        if (!$this->isContentTypeSupported($request)) {
            return new Response('', Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $headers);
        }

        try {
            $stream = $this->eventStore->load(new StreamName($streamName), (int)$version);
            foreach ($stream->streamEvents() as $event) {
                $body = $this->formatter->format($streamName, $event);
                // We only want the first event so immediately return a response.
                return new Response($body, Response::HTTP_OK, $headers);
            }

            return new Response('', Response::HTTP_NOT_FOUND, $headers);
        }
        catch (StreamNotFoundException $ex) {
            return new Response('', Response::HTTP_NOT_FOUND, $headers);
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isContentTypeSupported(Request $request)
    {
        return ($request->headers->get('Accept') === $this->formatter->getOutputContentType());
    }

}