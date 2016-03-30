<?php

namespace App\Tests\Integration\Cli;

use App\Tests\Integration\IntegrationTestCase;

class DeleteAllTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function it_deletes_all_the_data_from_cli()
    {
        $this->seedSampleData();
        $this->seeInDatabase('organisations', ['title' => 'Paradise Island']);
        $this->artisan('organisations:delete-all', ['--no-interaction' => true]);
        $this->notSeeInDatabase('organisations', ['title' => 'Paradise Island']);
    }
}
