<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

class StreamUriGenerator
{

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $streamName
     * @param int $minVersion
     * 
     * @return string
     */
    public function get($streamName, $minVersion = 0)
    {
        $routeParams = [
            'name' => $streamName,
            'minVersion' => $minVersion
        ];

        return $this->router->generate(StreamRouteName::GET, $routeParams);
    }

}