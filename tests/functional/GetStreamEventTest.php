<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Functional;

class GetStreamEventTest extends FunctionalTest
{

    /**
     * @test
     */
    public function it_should_return_200_ok_response()
    {
        $headers = ['HTTP_ACCEPT' => 'application/json'];
        $response = $this->request('GET', '/streams/user/events/3', [], [], $headers);

        $this->assertSame(200, $response->getStatusCode());
    }

}