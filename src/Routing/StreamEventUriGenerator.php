<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Routing;

use Prooph\Common\Messaging\Message;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class StreamEventUriGenerator
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
     * @param Message $event
     * 
     * @return string
     */
    public function get($streamName, Message $event)
    {
        $routeParams = [
            'streamName' => $streamName,
            'version' => $event->version()
        ];
        
        return $this->router->generate(StreamEventRouteName::GET, $routeParams);
    }

}