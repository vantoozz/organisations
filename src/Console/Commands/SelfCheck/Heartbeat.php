<?php declare(strict_types = 1);

namespace App\Console\Commands\SelfCheck;

use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

/**
 * Class Heartbeat
 * @package Vantoozz\Ensign\Console\Commands
 */
final class Heartbeat extends Command
{
    /**
     * @var string
     */
    protected $signature = 'self-check:heartbeat';

    /**
     * @var string
     */
    protected $description = 'Logs a string to application log';

    /**
     * @param LoggerInterface $logger
     * @return void
     */
    public function handle(LoggerInterface $logger): void
    {
        $logger->info('Heartbeat');
    }
}
