<?php declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Handler
 * @package App\Exceptions
 */
final class Handler implements ExceptionHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $ignoredExceptions = [];

    /**
     * Handler constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Exception $e
     */
    public function report(Exception $e): void
    {
        $this->logger->error($e, ['exception' => $e]);
    }

    /**
     * Render an exception into an HTTP response.
     * @param  Request $request
     * @param Exception $e
     * @return JsonResponse
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function render($request, Exception $e): JsonResponse
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }

        if ($e instanceof HttpResponseException) {
            $statusCode = $e->getResponse()->getStatusCode();
        }

        $errorMessage = $e->getMessage();
        if (empty($errorMessage) && array_key_exists($statusCode, Response::$statusTexts)) {
            $errorMessage = Response::$statusTexts[$statusCode];
        }

        return new JsonResponse([
            'error' => $errorMessage,
        ], $statusCode);
    }

    /**
     * @param OutputInterface $output
     * @param Exception $e
     */
    public function renderForConsole($output, Exception $e): void
    {
        (new ConsoleApplication)->renderException($e, $output);
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param Exception $e
     * @return bool
     */
    public function shouldReport(Exception $e)
    {
        return in_array($e->getCode(), $this->ignoredExceptions);
    }
}
