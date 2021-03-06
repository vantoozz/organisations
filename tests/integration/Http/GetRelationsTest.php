<?php

namespace App\Tests\Integration\Http;

use App\Tests\Integration\IntegrationTestCase;

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
                ['org_name' => 'Big banana tree', 'relationship_type' => 'parent']
            ]);
    }

    /**
     * @test
     */
    public function it_retrieves_next_page_relations()
    {
        $this->seedSampleData();
        $this->get('/api/v1/organisations/Black Banana/relations?page=3')
            ->seeJsonEquals([
                ['org_name' => 'Phoneutria Spider', 'relationship_type' => 'daughter'],
                ['org_name' => 'Yellow Banana', 'relationship_type' => 'sister']
            ]);
    }
}
