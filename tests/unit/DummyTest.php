<?php

namespace App;

use App\Tests\TestCase;

class DummyTest extends TestCase
{
    /**
     * @test
     */
    public function it_works()
    {
        static::assertSame(2 + 2, 4);
    }
}
