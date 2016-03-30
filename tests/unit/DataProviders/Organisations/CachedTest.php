<?php

namespace App\DataProviders\Organisations;

use App\Collections\OrganisationsCollection;
use App\Tests\TestCase;
use Illuminate\Contracts\Cache\Repository;

class CachedTest extends TestCase
{

    /**
     * @test
     */
    public function it_deletes_all_data()
    {
        $provider = static::getMock(OrganisationsDataProviderInterface::class);
        $cache = static::getMock(Repository::class);

        $provider
            ->expects(static::once())
            ->method('deleteAll');

        /** @var OrganisationsDataProviderInterface $provider */
        /** @var Repository $cache */
        $cachedProvider = new Cached($provider, $cache, 9000);

        $cachedProvider->deleteAll();
    }

    /**
     * @test
     */
    public function it_stores_relations()
    {
        $provider = static::getMock(OrganisationsDataProviderInterface::class);
        $cache = static::getMock(Repository::class);

        $organisations = new OrganisationsCollection();
        $ids = [1, 2, 3];

        $provider
            ->expects(static::once())
            ->method('storeRelations')
            ->with($organisations, $ids);

        /** @var OrganisationsDataProviderInterface $provider */
        /** @var Repository $cache */
        $cachedProvider = new Cached($provider, $cache, 9000);

        $cachedProvider->storeRelations($organisations, $ids);
    }

    /**
     * @test
     */
    public function it_retrieves_relations()
    {
        $provider = static::getMock(OrganisationsDataProviderInterface::class);
        $cache = static::getMock(Repository::class);

        $provider
            ->expects(static::once())
            ->method('getOrganisationRelations')
            ->with(1, 2, 3)
            ->willReturn(111);

        /** @var OrganisationsDataProviderInterface $provider */
        /** @var Repository $cache */
        $cachedProvider = new Cached($provider, $cache, 9000);

        static::assertSame(111, $cachedProvider->getOrganisationRelations(1, 2, 3));
    }

    /**
     * @test
     */
    public function it_retrieves_organisations_count()
    {
        $provider = static::getMock(OrganisationsDataProviderInterface::class);
        $cache = static::getMock(Repository::class);

        $provider
            ->expects(static::once())
            ->method('getOrganisationRelationsCount')
            ->with(123)
            ->willReturn(111);

        /** @var OrganisationsDataProviderInterface $provider */
        /** @var Repository $cache */
        $cachedProvider = new Cached($provider, $cache, 9000);

        static::assertSame(111, $cachedProvider->getOrganisationRelationsCount(123));
    }

    /**
     * @test
     */
    public function it_fetches_ids_by_titles_from_cache_only()
    {
        $provider = static::getMock(OrganisationsDataProviderInterface::class);
        $cache = static::getMock(Repository::class);

        $cache
            ->expects(static::at(0))
            ->method('has')
            ->with('CachedOrganisationsDataProviderInterface:0a5df84d2ed9a5d6060cdf4f58e76781a96d2211')
            ->willReturn(true);

        $cache
            ->expects(static::at(1))
            ->method('get')
            ->with('CachedOrganisationsDataProviderInterface:0a5df84d2ed9a5d6060cdf4f58e76781a96d2211')
            ->willReturn(111);

        $cache
            ->expects(static::at(2))
            ->method('has')
            ->with('CachedOrganisationsDataProviderInterface:3b82d45156e50f75ede95be10768a977b9b0fc28')
            ->willReturn(true);

        $cache
            ->expects(static::at(3))
            ->method('get')
            ->with('CachedOrganisationsDataProviderInterface:3b82d45156e50f75ede95be10768a977b9b0fc28')
            ->willReturn(222);

        /** @var OrganisationsDataProviderInterface $provider */
        /** @var Repository $cache */
        $cachedProvider = new Cached($provider, $cache, 9000);

        $data = $cachedProvider->fetchIdsByTitles(['one', 'two']);
        static::assertSame(['one' => 111, 'two' => 222], $data);
    }

    /**
     * @test
     */
    public function it_fetches_ids_by_titles_from_inner_provider()
    {
        $provider = static::getMock(OrganisationsDataProviderInterface::class);
        $cache = static::getMock(Repository::class);


        $cache
            ->expects(static::never())
            ->method('get');

        $cache
            ->expects(static::exactly(2))
            ->method('has')
            ->willReturn(false);

        $provider
            ->expects(static::once())
            ->method('fetchIdsByTitles')
            ->with(['one', 'two'])
            ->willReturn(['one' => 111, 'two' => 222]);


        $cache
            ->expects(static::at(2))
            ->method('put')
            ->with('CachedOrganisationsDataProviderInterface:0a5df84d2ed9a5d6060cdf4f58e76781a96d2211', 111, 9000);

        $cache
            ->expects(static::at(3))
            ->method('put')
            ->with('CachedOrganisationsDataProviderInterface:3b82d45156e50f75ede95be10768a977b9b0fc28', 222, 9000);

        /** @var OrganisationsDataProviderInterface $provider */
        /** @var Repository $cache */
        $cachedProvider = new Cached($provider, $cache, 9000);

        $data = $cachedProvider->fetchIdsByTitles(['one', 'two']);
        static::assertSame(['one' => 111, 'two' => 222], $data);
    }

    /**
     * @test
     */
    public function it_retrieves_organisation_by_id()
    {
        $provider = static::getMock(OrganisationsDataProviderInterface::class);
        $cache = static::getMock(Repository::class);

        $callback = function () {
            return 123;
        };

        $cache
            ->expects(static::once())
            ->method('remember')
            ->with('CachedOrganisationsDataProviderInterface:08b1e58ccfb6953fb6c1f57c77541405798a76d4', 9000, $callback)
            ->willReturn(111);


        /** @var OrganisationsDataProviderInterface $provider */
        /** @var Repository $cache */
        $cachedProvider = new Cached($provider, $cache, 9000);

        static::assertSame(111, $cachedProvider->getOrganisationId('one'));
    }
}
