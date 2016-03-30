<?php

namespace App\DataProviders\Organisations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Illuminate\Cache\TaggedCache;
use Illuminate\Contracts\Cache;
use Illuminate\Support\ServiceProvider;

/**
 * Class OrganisationsDataProviderServiceProvider
 * @package App\DataProviders\OrganisationsCollection
 */
class OrganisationsDataProviderServiceProvider extends ServiceProvider
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

            /** @var TaggedCache $secondaryCache */
            $secondaryCache = $this->app->make(Cache\Repository::class)->tags('OrganisationsDataProvider');
            /** @var TaggedCache $primaryCache */
            $primaryCache = $this->app->make(Cache\Factory::class)->store('array')->tags('OrganisationsDataProvider');

            $provider = new Cached($provider, $secondaryCache, 60);
            $provider = new Cached($provider, $primaryCache, 5);

            return $provider;
        });
    }
}
