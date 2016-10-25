<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Pagination\StreamPaginator;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing\StreamUriGenerator;
use Prooph\EventStore\Stream\Stream;

class JsonStreamFormatter implements StreamFormatter
{

    /**
     * @var StreamUriGenerator
     */
    private $uriGenerator;

    /**
     * @var StreamPaginator
     */
    private $paginator;

    /**
     * @var JsonStreamEventFormatter
     */
    private $eventFormatter;

    public function __construct(StreamUriGenerator $uriGenerator, StreamPaginator $paginator, JsonStreamEventFormatter $eventFormatter)
    {
        $this->uriGenerator = $uriGenerator;
        $this->paginator = $paginator;
        $this->eventFormatter = $eventFormatter;
    }

    public function getOutputContentType()
    {
        return 'application/json';
    }

    public function format(Stream $stream)
    {
        $streamName = (string) $stream->streamName();
        $links = $this->generateLinks($stream);
        $entries = $this->generateEntries($stream);

        return json_encode([
            'id' => $this->uriGenerator->get($streamName),
            'title' => $streamName . ' stream',
            'updated' => null,
            'links' => $links,
            'entries' => $entries
        ]);
    }

    /**
     * @param Stream $stream
     *
     * @return array
     */
    private function generateLinks(Stream $stream)
    {
        return [
            [
                'uri' => $this->paginator->next($stream),
                'relation' => 'next'
            ]
        ];
    }

    /**
     * @param Stream $stream
     *
     * @return array
     */
    private function generateEntries(Stream $stream)
    {
        $streamName = (string) $stream->streamName();

        $entries = [];
        foreach ($stream->streamEvents() as $event) {
            $entries[] = json_decode($this->eventFormatter->format($streamName, $event), true);
        }

        return $entries;
    }

}