<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\backoff;

use gordon\pdowrapper\backoff\Constant;
use PHPUnit\Framework\TestCase;

/**
 * @package gordon\pdowrapper\tests\unit\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group backoff-strategy
 */
class ConstantTest extends TestCase
{
    /**
     * Test constant backoff behaviour
     *
     * @return void
     */
    public function testBackoff()
    {
        $backoff = new Constant(12345);
        $this->assertSame(12345, $backoff->backoff());
        $this->assertSame(12345, $backoff->backoff());
    }
}
