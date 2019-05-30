<?php

namespace App\ServiceProviders;

use Doctrine\DBAL\Connection;
use Illuminate\Support\ServiceProvider;

/**
 * Class DbConnectionServiceProvider
 * @package App\Providers
 */
class DBALConnectionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Connection::class, function () {
            /** @var \Illuminate\Database\Connection $connection */
            $connection = $this->app->make('db')->connection();
            return $connection->getDoctrineConnection();
        });
    }
}
