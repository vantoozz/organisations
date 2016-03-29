<?php

namespace App\Tests\Integration;

class DeleteAllTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function it_deletes_all_the_data()
    {
        $this->seedSampleData();
        $this->seeInDatabase('organisations', ['title' => 'Paradise Island']);
        $this->delete('/api/v1/organisations')->seeStatusCode(204);
        $this->notSeeInDatabase('organisations', ['title' => 'Paradise Island']);
    }

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