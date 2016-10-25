<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Functional;

class GetStreamTest extends FunctionalTest
{

    /**
     * @test
     */
    public function it_should_return_200_ok_response()
    {
        $streamName = $this->faker->word;
        $minVersion = $this->faker->numberBetween();
        $headers = ['HTTP_ACCEPT' => 'application/json'];
        $response = $this->request('GET', '/streams/' . $streamName . '/' . $minVersion, [], [], $headers);

        $this->assertSame(200, $response->getStatusCode());
    }

}