<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\dsn\sqlite;

use gordon\pdowrapper\dsn\sqlite\DSN;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for SQLite DSN
 *
 * @package gordon\pdowrapper\tests\unit\dsn\sqlite
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group dsn
 */
class DSNTest extends TestCase
{
    /**
     * Test DSN for file path
     *
     * @return void
     */
    public function testDSNFile()
    {
        $dsn = new DSN("/path/to/database.sq3");
        $this->assertSame ("sqlite:/path/to/database.sq3", (string) $dsn);
    }

    /**
     * Test DSN for memory
     *
     * @return void
     */
    public function testDSNMemory()
    {
        $dsn = new DSN(DSN::MODE_MEMORY);
        $this->assertSame ("sqlite::memory:", (string) $dsn);
    }

    /**
     * Test DSN for temp file
     *
     * @return void
     */
    public function testDSNTemp()
    {
        $dsn = new DSN(DSN::MODE_TEMP);
        $this->assertSame ("sqlite:", (string) $dsn);
    }
}
