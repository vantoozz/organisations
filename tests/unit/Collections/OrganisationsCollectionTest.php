<?php

namespace App\Collections;

use App\Organisation;
use App\Tests\TestCase;

class OrganisationsCollectionTest extends TestCase
{

    /**
     * @test
     */
    public function it_returns_titles()
    {
        $collection = new OrganisationsCollection([
            new Organisation('one'),
            new Organisation('two')
        ]);
        static::assertSame(['one', 'two'], $collection->getTitles());
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage App\Collections\TypedCollection accepts elements of type App\Organisation only
     */
    public function it_checks_type_in_constructor()
    {
        new OrganisationsCollection([
            new \stdClass()
        ]);
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage App\Collections\TypedCollection accepts elements of type App\Organisation only
     */
    public function it_checks_type_on_put()
    {
        $collection = new OrganisationsCollection();
        $collection->put('aaa', 'aaa');
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage App\Collections\TypedCollection accepts elements of type App\Organisation only
     */
    public function it_checks_type_on_prepend()
    {
        $collection = new OrganisationsCollection();
        $collection->prepend('aaa', 'aaa');
    }

    /**
     * @test
     */
    public function it_prepends_organisation()
    {
        $collection = new OrganisationsCollection();
        $result = $collection->prepend(new Organisation('aaa'));
        static::assertCount(1, $result);
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage App\Collections\TypedCollection accepts elements of type App\Organisation only
     */
    public function it_checks_type_on_push()
    {
        $collection = new OrganisationsCollection();
        $collection->push('aaa');
    }
}
