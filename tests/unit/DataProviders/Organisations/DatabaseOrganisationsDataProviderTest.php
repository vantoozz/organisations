<?php

namespace App\DataProviders\Organisations;

use App\Collections\OrganisationsCollection;
use App\Organisation;
use App\Tests\TestCase;
use Doctrine\DBAL\Connection;

class DatabaseOrganisationsDataProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_deletes_all_the_data()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::at(0))
            ->method('exec')
            ->with('DELETE FROM `relations`');

        $connection
            ->expects(static::at(1))
            ->method('exec')
            ->with('DELETE FROM `organisations`');

        /** @var Connection $connection */
        $provider = new DatabaseOrganisationsDataProvider($connection);

        $provider->deleteAll();
    }

    /**
     * @test
     */
    public function it_stores_titles()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::at(0))
            ->method('executeQuery')
            ->with('SELECT `id`, `title` FROM `organisations` WHERE `title` IN (?)', [['one', 'two']])
            ->willReturnSelf();

        $connection
            ->expects(static::at(1))
            ->method('fetchAll')
            ->willReturn([['title' => 'one', 'id' => 111]]);

        $connection
            ->expects(static::at(2))
            ->method('insert')
            ->with('organisations', ['title' => 'two']);

        $connection
            ->expects(static::at(3))
            ->method('lastInsertId')
            ->willReturn(222);

        /** @var Connection $connection */
        $provider = new DatabaseOrganisationsDataProvider($connection);

        static::assertSame(['one' => 111, 'two' => 222], $provider->fetchIdsByTitles(['one', 'two']));
    }

    /**
     * @test
     */
    public function it_retrieves_relations()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::at(0))
            ->method('executeQuery')
            ->willReturnSelf();

        $connection
            ->expects(static::at(1))
            ->method('fetchAll')
            ->willReturn('some value');

        /** @var Connection $connection */
        $provider = new DatabaseOrganisationsDataProvider($connection);

        static::assertSame('some value', $provider->getOrganisationRelations(1, 2, 3));
    }

    /**
     * @test
     */
    public function it_retrieves_relations_count()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::at(0))
            ->method('executeQuery')
            ->willReturnSelf();

        $connection
            ->expects(static::at(1))
            ->method('fetchColumn')
            ->willReturn('9000');

        /** @var Connection $connection */
        $provider = new DatabaseOrganisationsDataProvider($connection);

        static::assertSame(9000, $provider->getOrganisationRelationsCount(123));
    }

    /**
     * @test
     */
    public function it_stores_relations()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $one = new Organisation('one');
        $two = new Organisation('two');
        $three = new Organisation('three');
        $four = new Organisation('four');
        $five = new Organisation('five');
        $one->addParent($two);
        $two->addParent($four);
        $two->addParent($five);
        $organisations = new OrganisationsCollection([$one, $two, $three]);

        $query = 'SELECT `parent_id` FROM relations WHERE `organisation_id` = :organisation_id;';

        $connection
            ->expects(static::at(0))
            ->method('executeQuery')
            ->with($query,  ['organisation_id' => 111])
        ->willReturnSelf();
        $connection
            ->expects(static::at(1))
            ->method('fetchAll')
            ->willReturn([]);
        $connection
            ->expects(static::at(2))
            ->method('insert')
            ->with('relations', ['organisation_id' => 111, 'parent_id' => 222])
            ->willReturn([]);
        $connection
            ->expects(static::at(3))
            ->method('executeQuery')
            ->with($query,  ['organisation_id' => 222])
            ->willReturnSelf();
        $connection
            ->expects(static::at(4))
            ->method('fetchAll')
            ->willReturn([['parent_id'=>2222], ['parent_id'=>444]]);
        /** @var Connection $connection */
        $provider = new DatabaseOrganisationsDataProvider($connection);

        $provider->storeRelations($organisations, ['one' => 111, 'two' => 222, 'four' => 444]);
    }

    /**
     * @test
     */
    public function it_retrieves_organisation_id()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::at(0))
            ->method('executeQuery')
            ->willReturnSelf();

        $connection
            ->expects(static::at(1))
            ->method('fetchColumn')
            ->willReturn('123');

        /** @var Connection $connection */
        $provider = new DatabaseOrganisationsDataProvider($connection);

        static::assertSame(123, $provider->getOrganisationId('one'));
    }

    /**
     * @test
     * @expectedException \App\Exceptions\NotFoundException
     * @expectedExceptionMessage No such organisation: one
     */
    public function it_throws_an_exception_if_organisation_not_found_by_id()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::at(0))
            ->method('executeQuery')
            ->willReturnSelf();

        $connection
            ->expects(static::at(1))
            ->method('fetchColumn')
            ->willReturn(false);

        /** @var Connection $connection */
        $provider = new DatabaseOrganisationsDataProvider($connection);

        static::assertSame(123, $provider->getOrganisationId('one'));
    }

}
