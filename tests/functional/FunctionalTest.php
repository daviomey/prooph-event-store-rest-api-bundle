<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Functional;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Functional\Symfony\AppKernel;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class FunctionalTest extends TestCase
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Client
     */
    protected $client;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::clearCache();
    }

    public function setUp()
    {
        parent::setUp();
        $this->bootKernelAndSetContainer();
        $this->client = $this->container->get('test.client');
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @param array $files
     * @param array $server
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function request($method, $uri, array $parameters = [], array $files = [], array $server = [])
    {
        $this->client->request($method, $uri, $parameters, $files, $server);
        return $this->client->getResponse();
    }

    private static function clearCache()
    {
        array_map('unlink', glob(__DIR__ . '/Symfony/cache/functional/*.*'));
    }

    private function bootKernelAndSetContainer()
    {
        $kernel = new AppKernel('functional', false);
        $kernel->boot();

        $this->container = $kernel->getContainer();
    }

}