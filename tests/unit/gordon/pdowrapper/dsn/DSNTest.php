<?php

namespace gordon\pdowrapper\tests\unit\dsn;

use gordon\pdowrapper\dsn\DSN;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for DSN
 *
 * @package gordon\pdowrapper\tests\unit\dsn
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group dsn
 */
class DSNTest extends TestCase
{
    /**
     * Test that the DSN object stores the DSN as provided
     *
     * @return void
     */
    public function testDSN()
    {
        $dsn = $this->getMockBuilder(DSN::class)
            ->onlyMethods([])
            ->setConstructorArgs(["Test DSN"])
            ->getMock();

        $this->assertSame("Test DSN", (string) $dsn);
    }

    /**
     * Test that the DSN object won't allow empty values
     */
    public function testDSNEmpty()
    {
        $this->expectException(\ValueError::class);
        $this->getMockBuilder(DSN::class)
            ->onlyMethods([])
            ->setConstructorArgs([""])
            ->getMock();
    }
}
