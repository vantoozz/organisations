<?php

namespace App\DataProviders\Organisations;

use App\Tests\TestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;

class OrganisationsDataProviderServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_data_provider()
    {
        $this->refreshApplication();
        $provider = new OrganisationsDataProviderServiceProvider($this->app);
        static::assertSame([OrganisationsDataProviderInterface::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_data_provider()
    {
        $this->refreshApplication();
        $provider = new OrganisationsDataProviderServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(
            OrganisationsDataProviderInterface::class,
            $this->app->make(OrganisationsDataProviderInterface::class)
        );
    }

    /**
     * @test
     */
    public function it_registers_mysql_data_provider()
    {
        $this->refreshApplication();
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $mysql = $this->createMock(AbstractMySQLDriver::class);
        $connection
            ->expects(static::once())
            ->method('getDriver')
            ->willReturn($mysql);
        $this->app->bind(Connection::class, function () use ($connection) {
            return $connection;
        });
        $provider = new OrganisationsDataProviderServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(
            OrganisationsDataProviderInterface::class,
            $this->app->make(OrganisationsDataProviderInterface::class)
        );
    }
}
