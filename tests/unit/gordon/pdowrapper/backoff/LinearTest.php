<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\backoff;

use gordon\pdowrapper\backoff\Linear;
use PHPUnit\Framework\TestCase;

/**
 * @package gordon\pdowrapper\tests\unit\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group backoff-strategy
 */
class LinearTest extends TestCase
{
    /**
     * Test linear backoff behaviour
     *
     * @return void
     */
    public function testBackoff()
    {
        $backoff = new Linear(initialBackoff: 100, factor: 100, clamp: 1000);
        $this->assertSame(100, $backoff->backoff());
        $this->assertSame(200, $backoff->backoff());
        $this->assertSame(300, $backoff->backoff());
        $this->assertSame(400, $backoff->backoff());
        $this->assertSame(500, $backoff->backoff());
        $this->assertSame(600, $backoff->backoff());
        $this->assertSame(700, $backoff->backoff());
        $this->assertSame(800, $backoff->backoff());
        $this->assertSame(900, $backoff->backoff());
        $this->assertSame(1000, $backoff->backoff());
    }

    /**
     * Test that the backoff stops at the clamp value
     *
     * @return void
     */
    public function testBackoffClamp()
    {
        $backoff = new Linear(initialBackoff: 100, factor: 500, clamp: 1000);
        $this->assertSame(100, $backoff->backoff());
        $this->assertSame(600, $backoff->backoff());
        $this->assertSame(1000, $backoff->backoff());
        $this->assertSame(1000, $backoff->backoff());
    }

    /**
     * Test strategy state reset
     *
     * @return void
     */
    public function testReset()
    {
        $backoff = new Linear(initialBackoff: 100, factor: 100, clamp: 1000);
        $this->assertSame(100, $backoff->backoff());
        $this->assertSame(200, $backoff->backoff());
        $this->assertSame(300, $backoff->backoff());
        $this->assertSame(100, $backoff->reset()->backoff());
    }
}
