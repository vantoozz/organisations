<?php

namespace App\Hydrators\RelationsCollection;

use App\Collections\RelationsCollection;
use App\Organisation;
use App\Relation;
use App\RelationType;
use App\Tests\TestCase;

class ArrayRelationsCollectionHydratorTest extends TestCase
{

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Resource must be an instance of App\Collections\RelationsCollection
     */
    public function it_throws_an_exception_if_extract_wrong_resource()
    {
        (new ArrayRelationsCollectionHydrator())->extract(new \stdClass);
    }


    /**
     * @test
     */
    public function it_hydrates_a_collection()
    {
        $hydrator = new ArrayRelationsCollectionHydrator();
        static::assertEquals(new RelationsCollection(), $hydrator->hydrate([]));
    }

    /**
     * @test
     */
    public function it_extracts_an_array()
    {
        $collection = new RelationsCollection();
        $collection->push(
            new Relation(new Organisation('aaa'), new Organisation('bbb'), new RelationType(RelationType::DAUGHTER))
        );
        $collection->push(
            new Relation(new Organisation('ccc'), new Organisation('ddd'), new RelationType(RelationType::SISTER))
        );

        $hydrator = new ArrayRelationsCollectionHydrator();
        static::assertSame(
            [
                ['org_name' => 'bbb', 'relationship_type' => 'daughter'],
                ['org_name' => 'ddd', 'relationship_type' => 'sister']
            ],
            $hydrator->extract($collection));
    }

}
