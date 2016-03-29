<?php

namespace App\ServiceProviders;

use App\Tests\TestCase;
use Faker\Generator;

class FakerServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_faker()
    {
        $this->refreshApplication();
        $provider = new FakerServiceProvider($this->app);
        static::assertSame([Generator::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_faker()
    {
        $this->refreshApplication();
        $provider = new DBALConnectionServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(Generator::class, $this->app->make(Generator::class));
    }
}
