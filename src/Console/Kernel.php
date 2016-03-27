<?php

namespace App\Console;

use App\Console\Commands\CreateOrganisationsCommand;
use App\Console\Commands\DeleteAllOrganisationsCommand;
use App\Console\Commands\GetOrganisationRelationsCommand;
use App\Console\Commands\SeedOrganisationsCommand;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SeedOrganisationsCommand::class,
        CreateOrganisationsCommand::class,
        DeleteAllOrganisationsCommand::class,
        GetOrganisationRelationsCommand::class,
    ];
}
