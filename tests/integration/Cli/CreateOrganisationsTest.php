<?php

namespace App\Tests\Integration\Cli;

use App\Tests\Integration\IntegrationTestCase;

class CreateOrganisationsTest extends IntegrationTestCase
{
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
