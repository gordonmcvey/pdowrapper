<?php

declare(strict_types=1);

namespace gordon\pdowrapper\dsn\sqlite;

use gordon\pdowrapper\dsn\DSN as AbstractDSN;

/**
 * Unit test for SQLite DSN
 *
 * @package gordon\pdowrapper\tests\unit\dsn
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @group dsn
 */
final class DSN extends AbstractDSN
{
    private const DRIVER_PREFIX = "sqlite";

    // Use this to indicate that the DSN that will initialise a SQLite database in memory
    public const MODE_MEMORY = true;

    // Use this to indicate that the DSN will initialise a temporary SQLite database
    public const MODE_TEMP = false;

    /**
     * SQLite can operate in one of 3 modes:
     *
     * * File: Specify the full path to the database file
     * * Memory: Specify MODE_MEMORY
     * * Temp file: Specify MODE_TEMP or an empty path string
     */
    public function __construct(string|bool $database)
    {
        if (false === $database) {
            $database = "";
        }

        // DSN initialisation
        parent::__construct(self::DRIVER_PREFIX . (true === $database ? "::memory:" : ":$database"));
    }
}
