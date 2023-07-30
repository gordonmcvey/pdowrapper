<?php

declare(strict_types=1);

namespace gordon\pdowrapper;

use gordon\pdowrapper\dsn\DSN;
use PDO;

/**
 *
 * @package gordon\pdowrapper
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
final readonly class ConnectionSpec
{
    public function __construct(
        public DSN     $dsn,
        public ?string $userName = null,
        public ?string $password = null,
        public ?array  $options = null,
        public ?string $pdoClass = PDO::class
    ) {}
}
