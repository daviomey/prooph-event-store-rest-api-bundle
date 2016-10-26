<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter;

use Prooph\EventStore\Stream\Stream;

interface StreamFormatter extends Formatter
{

    /**
     * @param Stream $stream
     * @param int $minVersion
     *
     * @return string
     */
    public function format(Stream $stream, $minVersion = 0);

}