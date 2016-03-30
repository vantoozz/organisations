<?php

namespace App\Tests\Integration\Http;

use App\Tests\Integration\IntegrationTestCase;

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
}
