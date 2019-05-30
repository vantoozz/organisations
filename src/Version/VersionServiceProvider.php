<?php declare(strict_types = 1);

namespace App\Version;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class VersionServiceProvider
 * @package App\Version
 * @property Application $app
 */
final class VersionServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        $this->app->singleton(VersionInterface::class, function () {
            return new Cached(new Unknown(new GitVersion($this->app->basePath())));
        });
    }
}
