<?php

namespace App\ServiceProviders;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class DbConnectionServiceProvider
 * @package App\Providers
 */
class DbConnectionServiceProvider extends ServiceProvider
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
        return [ConnectionInterface::class];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ConnectionInterface::class, function () {
            return $this->app->make('db')->connection();
        });
    }
}
