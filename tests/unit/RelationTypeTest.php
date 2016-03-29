<?php

namespace App;

use App\Tests\TestCase;

class RelationTypeTest extends TestCase
{
    /**
     * @test
     */
    public function it_stores_available_types()
    {
        static::assertSame(['parent', 'sister', 'daughter'], RelationType::getTypes());
    }

    /**
     * @test
     */
    public function it_stores_type()
    {
        $type = new RelationType('sister');
        static::assertSame('sister', $type->getType());
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Bad relation type: bad type
     */
    public function it_throws_an_exception_if_bad_type()
    {
        new RelationType('bad type');
    }
}
