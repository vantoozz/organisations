<?php

namespace App\Hydrators\OrganisationsCollection;

use App\Collections\OrganisationsCollection;
use App\Organisation;
use App\Tests\TestCase;

class JsonOrganisationsCollectionHydratorTest extends TestCase
{

    /**
     * @test
     */
    public function it_extracts_empty_string()
    {
        $hydrator = new JsonOrganisationsCollectionHydrator();
        static::assertSame('', $hydrator->extract(new OrganisationsCollection));
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Resource must be an instance of App\Collections\OrganisationsCollection
     */
    public function it_throws_an_exception_if_extract_wrong_resource()
    {
        (new JsonOrganisationsCollectionHydrator())->extract(new \stdClass);
    }


    /**
     * @test
     */
    public function it_hydrates_a_collection()
    {
        $hydrator = new JsonOrganisationsCollectionHydrator();
        $expected = new OrganisationsCollection();
        $parent = new Organisation('Banana tree');
        $organisation = new Organisation('Yellow Banana');
        $organisation->addParent($parent);
        $expected->put($organisation->getTitle(), $organisation);
        $organisation = new Organisation('Brown Banana');
        $organisation->addParent($parent);
        $expected->put($organisation->getTitle(), $organisation);
        $organisation = clone $parent;
        $parent = new Organisation('Paradise Island');
        $organisation->addParent($parent);
        $expected->put($organisation->getTitle(), $organisation);
        $organisation = clone $parent;
        $expected->put($organisation->getTitle(), $organisation);

        $hydrated = $hydrator->hydrate('{
            "org_name": "Paradise Island",
            "daughters": [
                {
                    "org_name": "Banana tree",
                    "daughters": [
                        {
                            "org_name": "Yellow Banana"
                        },
                        {
                            "org_name": "Brown Banana"
                        }
                    ]
                }
            ]
        }');

        static::assertEquals($expected, $hydrated);
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Bad input data. Error message: Syntax error
     */
    public function it_throws_an_exception_if_hydrate_bad_string()
    {
        (new JsonOrganisationsCollectionHydrator())->hydrate('bad string');
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage No org_name field
     */
    public function it_throws_an_exception_if_hydrate_json_without_org_name_field()
    {
        (new JsonOrganisationsCollectionHydrator())->hydrate('{"aaa":1}');
    }
}
