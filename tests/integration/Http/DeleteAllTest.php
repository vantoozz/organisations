<?php

namespace App\Tests\Integration\Http;

use App\Tests\Integration\IntegrationTestCase;

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
}
