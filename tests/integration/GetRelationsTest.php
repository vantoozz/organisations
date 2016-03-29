<?php

namespace App\Tests\Integration;

use Illuminate\Contracts\Console\Kernel;

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

    /**
     * @test
     */
    public function it_retrieves_relations_from_cli()
    {
        $this->seedSampleData();
        /** @var Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $this->artisan('organisations:relations', ['title' => 'Black Banana']);
        $expected = '[{"org_name":"Banana tree","relationship_type":"parent"}';
        $expected .= ',{"org_name":"Big banana tree","relationship_type":"parent"}]' . "\n";
        static::assertSame($expected, $kernel->output());
    }

    /**
     * @test
     */
    public function it_retrieves_next_page_relations_from_cli()
    {
        $this->seedSampleData();
        $kernel = $this->app->make(Kernel::class);
        $this->artisan('organisations:relations', ['title' => 'Black Banana', '--page' => 2]);
        $expected = '[{"org_name":"Brown Banana","relationship_type":"sister"}';
        $expected .= ',{"org_name":"Green Banana","relationship_type":"sister"}]' . "\n";
        static::assertSame($expected, $kernel->output());

    }
}
