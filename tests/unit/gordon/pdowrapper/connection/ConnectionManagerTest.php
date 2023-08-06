<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\connection;

use DomainException;
use gordon\pdowrapper\connection\ConnectionManager;
use gordon\pdowrapper\exception\PDOException;
use gordon\pdowrapper\interface\factory\IConnectionFactory;
use gordon\pdowrapper\tests\helpers\MockConnectionFactory;
use gordon\pdowrapper\tests\helpers\MockRealPOD;
use PDO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @package gordon\pdowrapper\tests\unit\connection
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group connection
 */
class ConnectionManagerTest extends TestCase
{
    use MockConnectionFactory;
    use MockRealPOD;

    private IConnectionFactory&MockObject $factory;

    private PDO&MockObject $db;

    /**
     * Initialise mocks for the tests
     */
    protected function setUp(): void
    {
        $this->factory = $this->mockConnectionFactory();
        $this->db = $this->mockRealPDO();
    }

    /**
     * Test that the connection manager has the expected default config
     */
    public function testConstructor(): void
    {
        // Setup
        $this->factory->method("get")->willReturn($this->db);
        $manager = new ConnectionManager($this->factory);

        // Assert the fixed config
        $this->assertSame(false, $manager->getAttribute(PDO::ATTR_EMULATE_PREPARES));
        $this->assertSame(PDO::ERRMODE_EXCEPTION, $manager->getAttribute(PDO::ATTR_ERRMODE));
        $this->assertSame(false, $manager->getAttribute(PDO::ATTR_STRINGIFY_FETCHES));

        // Assert the default config
        $this->assertSame(PDO::CASE_NATURAL, $manager->getAttribute(PDO::ATTR_CASE));
    }

    /**
     * Test the happy path for getConnection
     */
    public function testGetConnection(): void
    {
        // Setup
        $this->factory->method("get")->willReturn($this->db);
        $manager = new ConnectionManager($this->factory);
        $connection = $manager->getConnection();

        // Assert we get a PDO object back
        $this->assertInstanceOf(PDO::class, $connection);

        // Assert we get the same PDO object back on subsequent calls
        $this->assertSame($connection, $manager->getConnection());
    }

    /**
     * Test that exceptions are thrown if the connection could not be established
     */
    public function testGetConnectionFailure(): void
    {
        // Setup
        $this->factory->method("get")->willThrowException(new PDOException());
        $manager = new ConnectionManager($this->factory);

        // Set exception expectation
        $this->expectException(PDOException::class);

        // Run
        $manager->getConnection();
    }

    /**
     * Test that we get the same PDO object back on subsequent calls
     */
    public function testIsConnected(): void
    {
        $this->factory->method("get")->willReturn($this->db);
        $manager = new ConnectionManager($this->factory);

        $this->assertFalse($manager->isConnected());
        $manager->getConnection();
        $this->assertTrue($manager->isConnected());
    }

    /**
     * Test the operation of getConnectionIfConnected
     */
    public function testGetConnectionIfConnected(): void
    {
        $this->factory->method("get")->willReturn($this->db);
        $manager = new ConnectionManager($this->factory);

        $this->assertNull($manager->getConnectionIfConnected());
        $manager->getConnection();
        $this->assertInstanceOf(PDO::class, $manager->getConnectionIfConnected());
    }

    /**
     * Test connection expiry
     */
    public function testExpireConnection(): void
    {
        $this->factory->method("get")->willReturn($this->db);
        $manager = new ConnectionManager($this->factory);
        $manager->getConnection();

        $this->assertInstanceOf(PDO::class, $manager->getConnectionIfConnected());
        $manager->expireConnection();
        $this->assertNull($manager->getConnectionIfConnected());
    }

    /**
     * Test the happy path for setAttribute
     */
    public function testSetAttribute(): void
    {
        $manager = new ConnectionManager($this->factory);
        $this->assertTrue($manager->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER));
    }

    /**
     * Test that certain attributes cannot be modified
     */
    public function testSetAttributeImmutable(): void
    {
        $manager = new ConnectionManager($this->factory);
        $this->expectException(DomainException::class);
        $manager->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    }

    /**
     * Test the happy path for getAttribute
     */
    public function testGetAttribute(): void
    {
        $manager = new ConnectionManager($this->factory);

        $manager->setAttribute(PDO::ATTR_TIMEOUT, 600);
        $this->assertSame(600, $manager->getAttribute(PDO::ATTR_TIMEOUT));
    }

    /**
     * Test that we get a null back for unspecified attributes
     */
    public function testGetAttributeNotSet(): void
    {
        $manager = new ConnectionManager($this->factory);
        $this->assertNull($manager->getAttribute(PDO::ATTR_CURSOR));
    }
}
