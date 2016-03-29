<?php

namespace App;

use App\Tests\TestCase;

class OrganisationTest extends TestCase
{
    /**
     * @test
     */
    public function it_stores_title()
    {
        $organisation = new Organisation('some title');
        static::assertSame('some title', $organisation->getTitle());
    }

    /**
     * @test
     */
    public function it_stores_parents()
    {
        $organisation = new Organisation('some title');
        $parent = new Organisation('parent');
        $organisation->addParent($parent);
        static::assertSame($organisation->getParents()->first(), $parent);
    }
}
