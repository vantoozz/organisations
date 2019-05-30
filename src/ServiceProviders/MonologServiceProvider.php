<?php declare(strict_types=1);

namespace App\ServiceProviders;

use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Raven_Breadcrumbs_MonologHandler;
use Raven_Client;
use App\Exceptions\RuntimeException;
use App\Version\VersionInterface;

/**
 * Class MonologServiceProvider
 * @package App\ServiceProviders
 * @property Application $app
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class MonologServiceProvider extends ServiceProvider
{
    /**
     * @throws Exception
     */
    public function register(): void
    {
        $this->app->singleton(Logger::class, function () {
            $appName = env('APP_NAME', 'laravel');
            $logsPath = env('LOGS_PATH', storage_path('logs'));
            $logsPath = rtrim($logsPath, '/') . '/';

            $logger = new Logger($appName);

            $formatter =
                ('json' === env('LOGS_FORMATTER', 'json')) ?
                    new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES) :
                    new LineFormatter(null, 'Y-m-d H:i:s.u', true, true);

            $maxFiles = 2;
            if (env('APP_DEBUG')) {
                $logger->pushHandler(
                    (new RotatingFileHandler($logsPath . $appName . '.debug.log', $maxFiles, Logger::DEBUG))
                        ->setFormatter($formatter)
                );
            }

            $logger->pushProcessor(new MemoryUsageProcessor(true, false));

            $logger->pushHandler(
                (new RotatingFileHandler($logsPath . $appName . '.log', $maxFiles, Logger::INFO, false))
                    ->setFormatter($formatter)
            );

            $logger->pushHandler(
                (new RotatingFileHandler($logsPath . $appName . '.error.log', $maxFiles, Logger::WARNING, false))
                    ->setFormatter($formatter)
            );

            $sentryDsn = env('SENTRY_DSN');
            if ($sentryDsn) {
                $ravenClient = new Raven_Client($sentryDsn, [
                    'environment' => gethostname(),
                ]);

                $ravenHandler = new RavenHandler($ravenClient, Logger::WARNING, true);

                /** @var VersionInterface $versionProvider */
                $versionProvider = $this->app->make(VersionInterface::class);
                try {
                    $version = $versionProvider->getVersion();
                } catch (RuntimeException $e) {
                    $version = '';
                }
                $ravenHandler->setRelease($version);
                $ravenHandler->setFormatter(new LineFormatter('%message%'));

                $logger->pushHandler($ravenHandler);

                $breadcrumbHandler = new Raven_Breadcrumbs_MonologHandler($ravenClient, Logger::ERROR, true);
                $logger->pushHandler($breadcrumbHandler);

                $logger->pushProcessor(new IntrospectionProcessor());
            }

            return $logger;
        });
    }
}
