<?php

namespace App\DataProviders\Organisations;

use App\Collections\OrganisationsCollection;
use App\Organisation;
use App\Tests\TestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;

class MysqlOrganisationsDataProviderTest extends TestCase
{

    /**
     * @test
     */
    public function it_truncates_tables()
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::at(0))
            ->method('executeQuery')
            ->willReturnSelf();
        $connection
            ->expects(static::at(1))
            ->method('fetchColumn')
            ->willReturn('some value');
        $connection
            ->expects(static::at(5))
            ->method('executeQuery')
            ->with('SET foreign_key_checks=:status;', ['some value']);

        /** @var Connection $connection */
        $provider = new MysqlOrganisationsDataProvider($connection);

        $provider->deleteAll();
    }

    /**
     * @test
     */
    public function it_stores_relations()
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $one = new Organisation('one');
        $two = new Organisation('two');
        $three = new Organisation('three');
        $one->addParent($two);
        $organisations = new OrganisationsCollection([$one, $two, $three]);

        $connection
            ->expects(static::once())
            ->method('exec')
            ->with('
            INSERT INTO `relations` (`organisation_id`, `parent_id`) 
            VALUES (123, 222) 
            ON DUPLICATE KEY UPDATE parent_id = parent_id
            ;');

        /** @var Connection $connection */
        $provider = new MysqlOrganisationsDataProvider($connection);

        $provider->storeRelations($organisations, ['one' => 123, 'two' => 222, 'four' => 234]);
    }

    /**
     * @test
     */
    public function it_do_not_stores_relations()
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::never())
            ->method('exec');

        /** @var Connection $connection */
        $provider = new MysqlOrganisationsDataProvider($connection);

        $provider->storeRelations(new OrganisationsCollection(), ['one' => 123, 'two' => 222, 'four' => 234]);
    }

    /**
     * @test
     */
    public function it_stores_titles()
    {
        $statement = $this->createMock(Statement::class);
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

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
            ->method('prepare')
            ->willReturn($statement);

        $statement
            ->expects(static::at(0))
            ->method('bindValue')
            ->with(1, 'two');

        $statement
            ->expects(static::at(1))
            ->method('execute');

        $connection
            ->expects(static::at(3))
            ->method('executeQuery')
            ->with('SELECT `id`, `title` FROM `organisations` WHERE `title` IN (?)', [['two']])
            ->willReturnSelf();

        $connection
            ->expects(static::at(4))
            ->method('fetchAll')
            ->willReturn([['title' => 'two', 'id' => 222]]);

        /** @var Connection $connection */
        $provider = new MysqlOrganisationsDataProvider($connection);

        static::assertSame(['one' => 111, 'two' => 222], $provider->fetchIdsByTitles(['one', 'two']));
    }
}
