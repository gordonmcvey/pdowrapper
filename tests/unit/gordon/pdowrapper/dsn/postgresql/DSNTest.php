<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\dsn\postgresql;

use ArgumentCountError;
use gordon\pdowrapper\dsn\postgresql\DSN;
use PHPUnit\Framework\TestCase;
use ValueError;

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

        $this->assertSame("pgsql:host=example.com", (string) $dsn);
    }

    /**
     * Test DSN for specified host and port
     *
     * @return void
     */
    public function testDSNHostWithPort()
    {
        $dsn = new DSN(host: "example.com", port: 33066);

        $this->assertSame("pgsql:host=example.com;port=33066", (string) $dsn);
    }

    /**
     * Test DSN with optional DB name set
     *
     * @return void
     */
    public function testDSNWithDBName()
    {
        $dsn = new DSN(host: "example.com", dbName: "test_database");

        $this->assertSame("pgsql:host=example.com;dbname=test_database", (string) $dsn);
    }

    /**
     * Test DSN with optional username set
     *
     * @return void
     */
    public function testDSNWithUser()
    {
        $dsn = new DSN(host: "example.com", user: "test_user");

        $this->assertSame("pgsql:host=example.com;user=test_user", (string) $dsn);
    }

    /**
     * Test DSN with optional password set
     *
     * @return void
     */
    public function testDSNWithPassword()
    {
        $dsn = new DSN(host: "example.com", password: "test_password");

        $this->assertSame("pgsql:host=example.com;password=test_password", (string) $dsn);
    }

    /**
     * Test DSN with optional password set
     *
     * @return void
     */
    public function testDSNWithSslMode()
    {
        $dsn = new DSN(host: "example.com", sslMode: "test_ssl_mode");

        $this->assertSame("pgsql:host=example.com;sslmode=test_ssl_mode", (string) $dsn);
    }

    /**
     * Test that you can't instantiate a DSN with neither a host nor a socket
     *
     * @return void
     */
    public function testDSNNoHostOrSocket()
    {
        $this->expectException(ArgumentCountError::class);
        new DSN();
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
