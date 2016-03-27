<?php

namespace App\Repositories\Relations;

use App\DataProviders\Organisations\OrganisationsDataProviderInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class RelationsRepositoryServiceProvider
 * @package App\Repositories\Relations
 */
class RelationsRepositoryServiceProvider extends ServiceProvider
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
        return [RelationsRepositoryInterface::class];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RelationsRepositoryInterface::class, function () {
            /** @var OrganisationsDataProviderInterface $dataProvider */
            $dataProvider = $this->app->make(OrganisationsDataProviderInterface::class);
            return new DatabaseRelationsRepository($dataProvider, env('RELATIONS_PER_PAGE', 100));
        });
    }
}
