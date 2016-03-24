<?php

namespace App\Console;

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
        SeedOrganisationsCommand::class
    ];
}
