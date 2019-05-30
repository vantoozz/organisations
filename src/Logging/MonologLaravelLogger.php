<?php declare(strict_types = 1);

namespace App\Logging;

use Monolog\Logger;

/**
 * Class MonologLaravelLogger
 * @package App\Logging
 */
final class MonologLaravelLogger
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * MonologLaravelLogger constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Logger
     * @throws \Exception
     */
    public function __invoke(): Logger
    {
        return $this->logger;
    }
}
