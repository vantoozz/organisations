<?php

namespace App;

use App\Tests\TestCase;

class RelationTest extends TestCase
{

    /**
     * @test
     */
    public function it_stores_from_organisation()
    {
        $from = new Organisation('from');
        $to = new Organisation('to');
        $type = new RelationType(RelationType::SISTER);
        $relation  = new Relation($from, $to, $type);
        static::assertSame($from, $relation->getFrom());
    }

    /**
     * @test
     */
    public function it_stores_to_organisation()
    {
        $from = new Organisation('from');
        $to = new Organisation('to');
        $type = new RelationType(RelationType::SISTER);
        $relation  = new Relation($from, $to, $type);
        static::assertSame($to, $relation->getTo());
    }

    /**
     * @test
     */
    public function it_stores_relation_type()
    {
        $from = new Organisation('from');
        $to = new Organisation('to');
        $type = new RelationType(RelationType::SISTER);
        $relation  = new Relation($from, $to, $type);
        static::assertSame($type, $relation->getRelationType());
    }
}
