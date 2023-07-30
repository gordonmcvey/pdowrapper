<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\dsn\mysql;

use gordon\pdowrapper\dsn\mysql\DSN;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * Unit test for MySQL DSN
 *
 * @package gordon\pdowrapper\tests\unit\dsn
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group dsn
 */
class DSNTest extends TestCase
{
    /**
     * Test DSN for specified host
     *
     * @return void
     */
    public function testDSNHost()
    {
        $dsn = new DSN(host: "example.com");

        $this->assertSame("mysql:host=example.com", (string) $dsn);
    }

    /**
     * Test DSN for specified host and port
     *
     * @return void
     */
    public function testDSNHostWithPort()
    {
        $dsn = new DSN(host: "example.com", port: 33066);

        $this->assertSame("mysql:host=example.com;port=33066", (string) $dsn);
    }

    /**
     * Test DSN for specified socket
     *
     * @return void
     */
    public function testDSNSocket()
    {
        $dsn = new DSN(socket: "mysql.sock");

        $this->assertSame("mysql:unix_socket=mysql.sock", (string) $dsn);

    }

    /**
     * Test DSN with optional DB name set
     *
     * @return void
     */
    public function testDSNWithDBName()
    {
        $dsn = new DSN(host: "example.com", dbName: "test_database");

        $this->assertSame("mysql:host=example.com;dbname=test_database", (string) $dsn);
    }

    /**
     * Test DSN with optional charset set
     *
     * @return void
     */
    public function testDSNWithCharset()
    {
        $dsn = new DSN(host: "example.com", charset: "utf8");

        $this->assertSame("mysql:host=example.com;charset=utf8", (string) $dsn);
    }

    /**
     * Test that you can't instantiate a DSN with neither a host nor a socket
     *
     * @return void
     */
    public function testDSNNoHostOrSocket()
    {
        $this->expectException(ValueError::class);
        new DSN();
    }

    /**
     * Test that you can't instantiate a DSN with both a host and a socket
     *
     * @return void
     */
    public function testDSNBothHostAndSocket()
    {
        $this->expectException(ValueError::class);
        new DSN(host: "example.com", socket: "mysql.sock");
    }

    /**
     * Test that you can't instantiate a socket DSN with a port
     *
     * @return void
     */
    public function testDSNSocketAndPort()
    {
        $this->expectException(ValueError::class);
        new DSN(port: 33066, socket: "mysql.sock");
    }

    public function testDSNPortOutOfRangeMin()
    {
        $this->expectException(ValueError::class);
        new DSN(host: "example.com", port: 0);
    }

    public function testDSNPortOutOfRangeMax()
    {
        $this->expectException(ValueError::class);
        new DSN(host: "example.com", port: 65536);
    }
}
