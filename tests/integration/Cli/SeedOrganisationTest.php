<?php

namespace App\Tests\Integration\Cli;

use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Contracts\Console\Kernel;

class SeedOrganisationTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function it_retrieves_next_page_relations_from_cli()
    {
        $this->seedSampleData();
        $kernel = $this->app->make(Kernel::class);
        $this->artisan('organisations:seed', ['count' => 100]);
        static::assertStringEndsWith("Organisations saved\n", $kernel->output());
        $this->seeInDatabase('organisations', ['title' => 'Seeded organisation']);
    }
}
