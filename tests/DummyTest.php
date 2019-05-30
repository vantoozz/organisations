<?php declare(strict_types = 1);

namespace App;

use PHPUnit\Framework\TestCase;

/**
 * Class DummyTest
 * @package App
 */
final class DummyTest extends TestCase
{
    /**
     * @test
     */
    public function it_works(): void
    {
        $this->assertTrue(true);
    }
}
