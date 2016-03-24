<?php

namespace App;

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
