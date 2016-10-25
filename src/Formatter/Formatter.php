<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Formatter;

interface Formatter
{

    /**
     * @return string
     */
    public function getOutputContentType();

}