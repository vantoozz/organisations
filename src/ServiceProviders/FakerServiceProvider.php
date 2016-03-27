<?php

namespace App\ServiceProviders;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

/**
 * Class FakerServiceProvider
 * @package App\Providers
 */
class FakerServiceProvider extends ServiceProvider
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
        return [Generator::class];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Generator::class, function () {
            return Factory::create();
        });
    }
}
