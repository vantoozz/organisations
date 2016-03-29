<?php

namespace App\Tests\Integration;

class CreateOrganisationsTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function it_deletes_all_the_data()
    {
        $this->notSeeInDatabase('organisations', ['title' => 'Paradise Island']);
        $this->call('POST', '/api/v1/organisations', [], [], [], [], $this->getSample());
        $this->seeInDatabase('organisations', ['title' => 'Paradise Island']);
    }
}