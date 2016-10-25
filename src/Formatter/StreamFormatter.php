<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter;

use Prooph\EventStore\Stream\Stream;

interface StreamFormatter extends Formatter
{

    /**
     * @param Stream $stream
     *
     * @return string
     */
    public function format(Stream $stream);

}