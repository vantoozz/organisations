<?php

namespace App\ServiceProviders;

use App\Tests\TestCase;
use Whoops\Run;

class WhoopsServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_whoops()
    {
        $this->refreshApplication();
        $provider = new WhoopsServiceProvider($this->app);
        static::assertSame([Run::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_whoops()
    {
        $this->refreshApplication();
        $provider = new WhoopsServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(Run::class, $this->app->make(Run::class));
    }
}
