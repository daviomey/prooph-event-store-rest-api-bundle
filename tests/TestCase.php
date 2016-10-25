<?php
namespace Ibanawx\Bundle\Prooph\EventStore\RestApiBundle\Tests;

use Faker\Factory;
use Faker\Generator;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Generator
     */
    protected $faker;

    public function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

}