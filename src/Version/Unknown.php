<?php declare(strict_types = 1);

namespace App\Version;

use App\Exceptions\RuntimeException;

/**
 * Class Unknown
 * @package App\Version
 */
final class Unknown implements VersionInterface
{
    /**
     * @var VersionInterface
     */
    private $version;

    /**
     * Unknown constructor.
     * @param VersionInterface $version
     */
    public function __construct(VersionInterface $version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        try {
            return $this->version->getVersion();
        } catch (RuntimeException $e) {
            return 'unknown';
        }
    }
}
