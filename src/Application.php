<?php declare(strict_types = 1);

namespace App;

/**
 * Class Application
 * @package App
 */
final class Application extends \Illuminate\Foundation\Application
{
    /**
     * @param string $path
     * @return string
     */
    public function path($path = ''): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'src' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
