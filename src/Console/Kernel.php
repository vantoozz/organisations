<?php declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SelfCheck\Heartbeat;

/**
 * Class Kernel
 * @package App\Console
 */
final class Kernel extends ConsoleKernel
{

    /**
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule
            ->command(Heartbeat::class)
            ->withoutOverlapping()
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
