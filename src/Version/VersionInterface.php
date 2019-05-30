<?php declare(strict_types = 1);

namespace App\Version;

use App\Exceptions\RuntimeException;

/**
 * Interface VersionInterface
 * @package App\Version
 */
interface VersionInterface
{
    /**
     * @return string
     * @throws RuntimeException
     */
    public function getVersion(): string;
}
