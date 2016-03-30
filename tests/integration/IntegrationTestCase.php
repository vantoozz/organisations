<?php

namespace App\Tests\Integration;

use App\Hydrators\OrganisationsCollection\JsonOrganisationsCollectionHydrator;
use App\Repositories\Organisations\OrganisationsRepositoryInterface;
use App\Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class IntegrationTestCase
 * @package App\Tests\Integration
 */
abstract class IntegrationTestCase extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     */
    protected function seedSampleData()
    {
        /** @var OrganisationsRepositoryInterface $repository */
        $repository = $this->app->make(OrganisationsRepositoryInterface::class);
        /** @var JsonOrganisationsCollectionHydrator $hydrator */
        $hydrator = $this->app->make(JsonOrganisationsCollectionHydrator::class);

        $repository->store($hydrator->hydrate($this->getSample()));
    }

    /**
     * @return mixed
     */
    protected function getSample()
    {
        return file_get_contents(__DIR__ . '/samples/input.json');
    }
}
