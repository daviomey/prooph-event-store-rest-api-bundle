<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Controller;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter\StreamFormatter;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Exception\StreamNotFoundException;
use Prooph\EventStore\Stream\StreamName;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StreamsController
{

    /**
     * @var StreamFormatter
     */
    private $formatter;

    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(StreamFormatter $formatter, EventStore $eventStore)
    {
        $this->formatter = $formatter;
        $this->eventStore = $eventStore;
    }

    /**
     * @param Request $request
     * @param string $streamName
     * @param int $minVersion
     *
     * @return Response
     */
    public function getAction(Request $request, $streamName, $minVersion = 0)
    {
        $headers = ['Content-Type' => $this->formatter->getOutputContentType()];

        if (!$this->isContentTypeSupported($request)) {
            return new Response('', Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $headers);
        }

        try {
            $stream = $this->eventStore->load(new StreamName($streamName), $minVersion);
            $body = $this->formatter->format($stream);

            return new Response($body, Response::HTTP_OK, $headers);
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