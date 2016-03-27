<?php

namespace App\Repositories\Organisations;

use App\DataProviders\Organisations\OrganisationsDataProviderInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Illuminate\Contracts\Cache;
use Illuminate\Support\ServiceProvider;

/**
 * Class OrganisationsRepositoryServiceProvider
 * @package App\Repositories\Organisations
 */
class OrganisationsRepositoryServiceProvider extends ServiceProvider
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
        return [OrganisationsRepositoryInterface::class];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OrganisationsRepositoryInterface::class, function () {
            /** @var Connection $connection */
            $connection = $this->app->make(Connection::class);
            /** @var OrganisationsDataProviderInterface $dataProvider */
            $dataProvider = $this->app->make(OrganisationsDataProviderInterface::class);

            if ($connection->getDriver() instanceof AbstractMySQLDriver) {
                return new MysqlOrganisationsRepository($connection, $dataProvider);
            }
            
            return new DatabaseOrganisationsRepository($connection, $dataProvider);
        });
    }
}
