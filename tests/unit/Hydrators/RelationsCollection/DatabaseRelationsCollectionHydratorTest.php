<?php

namespace App\Hydrators\RelationsCollection;

use App\Collections\RelationsCollection;
use App\Organisation;
use App\Relation;
use App\RelationType;
use App\Tests\TestCase;

class DatabaseRelationsCollectionHydratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_extracts_empty_string()
    {
        $hydrator = new DatabaseRelationsCollectionHydrator();
        static::assertSame('', $hydrator->extract(new RelationsCollection()));
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Resource must be an instance of App\Collections\RelationsCollection
     */
    public function it_throws_an_exception_if_extract_wrong_resource()
    {
        (new DatabaseRelationsCollectionHydrator())->extract(new \stdClass);
    }

    /**
     * @test
     */
    public function it_hydrates_a_collection()
    {
        $expected = new RelationsCollection();
        $expected->push(
            new Relation(new Organisation('aaa'), new Organisation('bbb'), new RelationType(RelationType::DAUGHTER))
        );
        $expected->push(
            new Relation(new Organisation('ccc'), new Organisation('ddd'), new RelationType(RelationType::SISTER))
        );

        $hydrator = new DatabaseRelationsCollectionHydrator();
        $hydrated = $hydrator->hydrate([
            ['from' => 'aaa', 'to' => 'bbb', 'relation' => 'daughter'],
            ['from' => 'ccc', 'to' => 'ddd', 'relation' => 'sister'],
        ]);
        static::assertEquals($expected, $hydrated);
    }
}
