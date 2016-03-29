<?php

namespace App\ServiceProviders;

use App\Tests\TestCase;
use Doctrine\DBAL\Connection;

class DBALConnectionServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_dbal()
    {
        $this->refreshApplication();
        $provider = new DBALConnectionServiceProvider($this->app);
        static::assertSame([Connection::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_dbal()
    {
        $this->refreshApplication();
        $provider = new DBALConnectionServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(Connection::class, $this->app->make(Connection::class));
    }
}
