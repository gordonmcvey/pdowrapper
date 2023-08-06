<?php

declare(strict_types=1);

namespace gordon\pdowrapper\connection;

use gordon\pdowrapper\dsn\DSN;
use PDO;

/**
 *
 * @package gordon\pdowrapper
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
final readonly class ConnectionSpec
{
    /**
     * @param DSN $dsn
     * @param string|null $userName
     * @param string|null $password
     * @param array<int, scalar>|null $options
     * @param string|null $pdoClass
     */
    public function __construct(
        public DSN     $dsn,
        public ?string $userName = null,
        public ?string $password = null,
        public ?array  $options = null,
        public ?string $pdoClass = PDO::class
    ) {
    }
}
