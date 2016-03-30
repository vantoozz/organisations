<?php

namespace App\Repositories\Organisations;

use App\Tests\TestCase;

class OrganisationsRepositoryServiceProviderTest extends TestCase
{


    /**
     * @test
     */
    public function it_provides_repository()
    {
        $this->refreshApplication();
        $provider = new OrganisationsRepositoryServiceProvider($this->app);
        static::assertSame([OrganisationsRepositoryInterface::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_repository()
    {
        $this->refreshApplication();
        $provider = new OrganisationsRepositoryServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(
            OrganisationsRepositoryInterface::class,
            $this->app->make(OrganisationsRepositoryInterface::class)
        );
    }
}
