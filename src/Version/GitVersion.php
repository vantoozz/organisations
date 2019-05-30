<?php declare(strict_types = 1);

namespace App\Version;

use App\Exceptions\RuntimeException;

/**
 * Class GitVersion
 * @package App\Version
 */
final class GitVersion implements VersionInterface
{
    /**
     * @var string
     */
    private $repositoryPath;

    /**
     * GitVersion constructor.
     * @param string $repositoryPath
     */
    public function __construct(string $repositoryPath)
    {
        $this->repositoryPath = $repositoryPath;
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getVersion(): string
    {
        $command = 'git -C ' . escapeshellarg($this->repositoryPath) . '  rev-parse HEAD ';
        if (DIRECTORY_SEPARATOR === '/') {
            $command = 'LC_ALL=en_US.UTF-8 ' . $command;
        }
        exec($command, $output, $returnValue);

        if ($returnValue !== 0) {
            throw new RuntimeException(implode("\r\n", $output));
        }

        if (!array_key_exists(0, $output)) {
            throw new RuntimeException(implode("\r\n", $output));
        }

        return $output[0];
    }
}
