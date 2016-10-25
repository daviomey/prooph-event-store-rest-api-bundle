<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Unit;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\TestCase;

abstract class UnitTest extends TestCase
{

    /**
     * @param string $className
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mock($className)
    {
        return $this
            ->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();
    }

}