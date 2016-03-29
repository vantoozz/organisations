<?php

namespace App\Tests\Integration;

class GetRelationsTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function it_retrieves_relations()
    {
        $this->seedSampleData();
        $this->get('/api/v1/organisations/Black Banana/relations')
            ->seeJsonEquals([
                ['org_name' => 'Banana tree', 'relationship_type' => 'parent'],
                ['org_name' => 'Big banana tree', 'relationship_type' => 'parent'],
                ['org_name' => 'Brown Banana', 'relationship_type' => 'sister']
            ]);
    }
}