<?php

namespace App\Tests\Integration;

class CreateOrganisationsTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function it_creates_organisations()
    {
        $this->notSeeInDatabase('organisations', ['title' => 'Paradise Island']);
        $this->call('POST', '/api/v1/organisations', [], [], [], [], $this->getSample());
        $this->seeInDatabase('organisations', ['title' => 'Paradise Island']);
    }

    /**
     * @test
     */
    public function it_creates_organisations_from_cli()
    {
        $this->notSeeInDatabase('organisations', ['title' => 'Paradise Island']);
        $this->artisan('organisations:create', ['json' => $this->getSample()]);
        $this->seeInDatabase('organisations', ['title' => 'Paradise Island']);
    }
}