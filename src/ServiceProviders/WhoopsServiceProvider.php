<?php

namespace App\ServiceProviders;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class WhoopsServiceProvider
 * @package App\Providers
 */
class WhoopsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return array
     */
    public function provides()
    {
        return [Run::class];
    }

    /**
     * Register the service provider.
     * @return void
     * @throws \InvalidArgumentException
     */
    public function register()
    {
        $this->app->bind(Run::class, function () {
            $whoops = new Run;
            $handler = new PrettyPageHandler;
            $handler->handleUnconditionally(true);
            $whoops->pushHandler($handler);
            $whoops->writeToOutput(false);
            $whoops->allowQuit(false);

            return $whoops;
        });
    }
}
