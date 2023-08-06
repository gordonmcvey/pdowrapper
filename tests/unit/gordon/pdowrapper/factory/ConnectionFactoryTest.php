<?php

declare(strict_types=1);

namespace gordon\pdowrapper\tests\unit\factory;

use gordon\pdowrapper\connection\ConnectionSpec;
use gordon\pdowrapper\dsn\sqlite\DSN;
use gordon\pdowrapper\exception\InstantiationException;
use gordon\pdowrapper\factory\ConnectionFactory;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;

/**
 * @package gordon\pdowrapper\tests\unit\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group instantiation
 */
class ConnectionFactoryTest extends TestCase
{
    /**
     * Test the happy path for get
     */
    public function testGet(): void
    {
        $factory = new ConnectionFactory(new ConnectionSpec(
            new DSN(
                DSN::MODE_MEMORY
            )
        ));

        $this->assertInstanceOf(\PDO::class, $factory->get());
    }

    /**
     * Test that an invalid instantiation attempt throws an exception
     */
    public function testGetException(): void
    {
        $factory = new ConnectionFactory(new ConnectionSpec(
            new DSN(
                "/dev/null/asdf.sq3"
            )
        ));

        $this->expectException(InstantiationException::class);
        $factory->get();
    }

    /**
     * Test that the factory won't instantiate any class that doesn't inherit from \PDO
     */
    public function testGetInvalidClass(): void
    {
        $factory = new ConnectionFactory(new ConnectionSpec(
            dsn: new DSN(
                "/dev/null/asdf.sq3"
            ),
            pdoClass: stdClass::class
        ));

        $this->expectException(TypeError::class);
        $factory->get();
    }
}
