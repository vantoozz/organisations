<?php

namespace App\Repositories\Organisations;

use App\Collections\OrganisationsCollection;
use App\DataProviders\Organisations\OrganisationsDataProviderInterface;
use App\Hydrators\RelationsCollection\DatabaseRelationsCollectionHydrator;
use App\Organisation;
use App\Tests\TestCase;

class DatabaseOrganisationsRepositoryTest extends TestCase
{

    /**
     * @test
     */
    public function it_deletes_all_data()
    {
        $hydrator = new DatabaseRelationsCollectionHydrator;
        $provider = static::getMock(OrganisationsDataProviderInterface::class);

        $provider
            ->expects(static::once())
            ->method('deleteAll');

        /** @var OrganisationsDataProviderInterface $provider */
        $repository = new DatabaseOrganisationsRepository($provider, $hydrator, 3);
        $repository->deleteAll();
    }


    /**
     * @test
     */
    public function it_deletes_stores_collection()
    {
        $hydrator = new DatabaseRelationsCollectionHydrator;
        $provider = static::getMock(OrganisationsDataProviderInterface::class);

        $organisations = new OrganisationsCollection([
            new Organisation('one'),
            new Organisation('two')
        ]);

        $provider
            ->expects(static::once())
            ->method('fetchIdsByTitles')
            ->with(['one', 'two'])
            ->willReturn([1, 2]);
        $provider
            ->expects(static::once())
            ->method('storeRelations')
            ->with($organisations, [1, 2]);

        /** @var OrganisationsDataProviderInterface $provider */
        $repository = new DatabaseOrganisationsRepository($provider, $hydrator, 3);
        $repository->store($organisations);
    }

    /**
     * @test
     */
    public function it_fetches_relations_by_title()
    {
        $hydrator = static::getMock(DatabaseRelationsCollectionHydrator::class);
        $provider = static::getMock(OrganisationsDataProviderInterface::class);

        $provider
            ->expects(static::once())
            ->method('getOrganisationId')
            ->with('aaa')
            ->willReturn(123);
        $provider
            ->expects(static::once())
            ->method('getOrganisationRelationsCount')
            ->with(123)
            ->willReturn(9000);
        $provider
            ->expects(static::once())
            ->method('getOrganisationRelations')
            ->with(123, 3, 3)
            ->willReturn([['one' => 1], ['two' => 2]]);

        $hydrator
            ->expects(static::once())
            ->method('hydrate')
            ->with([['one' => 1, 'from'=>'aaa'], ['two' => 2, 'from'=>'aaa']]);

        /** @var OrganisationsDataProviderInterface $provider */
        /** @var DatabaseRelationsCollectionHydrator $hydrator */
        $repository = new DatabaseOrganisationsRepository($provider, $hydrator, 3);
        $repository->getRelationsByTitle('aaa', 2);
    }
}
