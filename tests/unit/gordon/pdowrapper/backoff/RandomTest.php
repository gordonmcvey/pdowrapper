<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\backoff;

use gordon\pdowrapper\backoff\Random;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * @package gordon\pdowrapper\tests\unit\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group backoff-strategy
 */
class RandomTest extends TestCase
{
    /**
     * Test Random backoff behaviour
     *
     * Due to being inherently random, we can't check exact values returned from the class, only that they are in the
     * range specified by the clamps
     */
    public function testBackoff()
    {
        $backoff = new Random(100, 200);
        $value = $backoff->backoff();

        $this->assertGreaterThanOrEqual(100, $value);
        $this->assertLessThanOrEqual(200, $value);
    }

    /**
     * Assert that the minimum clamp can't be negative
     * @return void
     */
    public function testBackoffClampMinOutOfRange()
    {
        $this->expectException(ValueError::class);
        new Random(-1, 42);
    }

    /**
     * Assert that the maximum clamp can't be negative
     * @return void
     */
    public function testBackoffClampMaxOutOfRange()
    {
        $this->expectException(ValueError::class);
        new Random(42, -1);
    }

    /**
     * Assert that the maximum clamp can't be less than the minimum clamp
     * @return void
     */
    public function testBackoffClampMaxBwlowClampMin()
    {
        $this->expectException(ValueError::class);
        new Random(42, 24);
    }
}
