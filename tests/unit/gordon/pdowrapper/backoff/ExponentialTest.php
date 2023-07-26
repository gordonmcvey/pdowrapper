<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\backoff;

use gordon\pdowrapper\backoff\Exponential;
use PHPUnit\Framework\TestCase;

/**
 * @package gordon\pdowrapper\tests\unit\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group backoff-strategy
 */
class ExponentialTest extends TestCase
{
    /**
     * Test exponential backoff behaviour
     *
     * @return void
     */
    public function testBackoff()
    {
        $backoff = new Exponential(initialBackoff: 1, factor: 2);
        $this->assertSame(1, $backoff->backoff());
        $this->assertSame(2, $backoff->backoff());
        $this->assertSame(4, $backoff->backoff());
        $this->assertSame(8, $backoff->backoff());
        $this->assertSame(16, $backoff->backoff());
        $this->assertSame(32, $backoff->backoff());
        $this->assertSame(64, $backoff->backoff());
        $this->assertSame(128, $backoff->backoff());
        $this->assertSame(256, $backoff->backoff());
        $this->assertSame(512, $backoff->backoff());
        $this->assertSame(1024, $backoff->backoff());
    }

    /**
     * Test that the backoff stops at the clamp value
     *
     * @return void
     */
    public function testBackoffClamp()
    {
        $backoff = new Exponential(initialBackoff: 200, factor: 2, clamp: 1000);
        $this->assertSame(200, $backoff->backoff());
        $this->assertSame(400, $backoff->backoff());
        $this->assertSame(800, $backoff->backoff());
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
        $backoff = new Exponential(initialBackoff: 200, factor: 2, clamp: 1000);
        $this->assertSame(200, $backoff->backoff());
        $this->assertSame(400, $backoff->backoff());
        $this->assertSame(800, $backoff->backoff());
        $this->assertSame(200, $backoff->reset()->backoff());
    }
}
