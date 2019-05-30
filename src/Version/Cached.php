<?php declare(strict_types = 1);

namespace App\Version;

/**
 * Class Cached
 * @package App\Version
 */
final class Cached implements VersionInterface
{
    /**
     * @var string
     */
    private $version = '';

    /**
     * @var VersionInterface
     */
    private $versionProvider;

    /**
     * Cached constructor.
     * @param VersionInterface $versionProvider
     */
    public function __construct(VersionInterface $versionProvider)
    {
        $this->versionProvider = $versionProvider;
    }

    /**
     * @return string
     * @throws \App\Exceptions\RuntimeException
     */
    public function getVersion(): string
    {
        if ('' === $this->version) {
            $this->version = $this->versionProvider->getVersion();
        }

        return $this->version;
    }
}
