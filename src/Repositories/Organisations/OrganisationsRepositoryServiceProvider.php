<?php

namespace App\Repositories\Organisations;

use Illuminate\Contracts\Cache;
use Illuminate\Support\ServiceProvider;

/**
 * Class OrganisationsRepositoryServiceProvider
 * @package App\Repositories\OrganisationsCollection
 */
class OrganisationsRepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @return array
     */
    public function provides()
    {
        return [OrganisationsRepositoryInterface::class];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            OrganisationsRepositoryInterface::class,
            DatabaseOrganisationsRepository::class
        );
    }
}
