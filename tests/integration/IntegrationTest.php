<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Integration;

use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\Integration\Symfony\AppKernel;
use Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class IntegrationTest extends TestCase
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::clearCache();
    }

    public function setUp()
    {
        parent::setUp();
        $this->bootKernelAndSetContainer();
    }

    private static function clearCache()
    {
        array_map('unlink', glob(__DIR__ . '/Symfony/cache/test/*.*'));
    }

    private function bootKernelAndSetContainer()
    {
        $kernel = new AppKernel('test', false);
        $kernel->boot();

        $this->container = $kernel->getContainer();
    }

}