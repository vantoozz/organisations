<?php

namespace App\DataProviders\Organisations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Illuminate\Contracts\Cache;
use Illuminate\Support\ServiceProvider;

/**
 * Class OrganisationsDataProviderServiceProvider
 * @package App\DataProviders\Organisations
 */
class OrganisationsDataProviderServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @return array
     */
    public function provides()
    {
        return [OrganisationsDataProviderInterface::class];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OrganisationsDataProviderInterface::class, function () {
            /** @var Connection $connection */
            $connection = $this->app->make(Connection::class);

            if ($connection->getDriver() instanceof AbstractMySQLDriver) {
                $provider = new MysqlOrganisationsDataProvider($connection);
            } else {
                $provider = new DatabaseOrganisationsDataProvider($connection);
            }

            /** @var Cache\Repository $secondaryCache */
            $secondaryCache = $this->app->make(Cache\Repository::class);
            /** @var Cache\Repository $primaryCache */
            $primaryCache = $this->app->make(Cache\Factory::class)->store('array');

            $provider = new Cached($provider, $secondaryCache, 60);
            $provider = new Cached($provider, $primaryCache, 5);

            return $provider;
        });
    }
}
