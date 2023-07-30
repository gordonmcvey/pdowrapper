<?php

declare(strict_types=1);

namespace gordon\pdowrapper\dsn\mysql;

use gordon\pdowrapper\dsn\DSN as AbstractDSN;
use ValueError;

/**
 * DSN for MySQL connections
 *
 * @package gordon\pdowrapper\dsn\mysql
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
final class DSN extends AbstractDSN
{
    private const DRIVER_PREFIX = "mysql";

    /**
     * @param string|null $host
     * @param int|null $port
     * @param string|null $dbName
     * @param string|null $socket
     * @param string|null $charset
     * @throws ValueError
     */
    public function __construct(
        ?string $host    = null,
        ?int    $port    = null,
        ?string $dbName  = null,
        ?string $socket  = null,
        ?string $charset = null
    )
    {
        // Input validation
        if (!($host xor $socket)) {
            throw new ValueError("Must specify a host or a socket, but cannot specify both");
        }

        if ($socket && $port) {
            throw new ValueError("Port cannot be specified for a UNIX socket");
        }

        $this->validatePort($port);

        // DSN initialisation
        parent::__construct(self::DRIVER_PREFIX . ":" . $this->buildParamString([
            "host"        => $host,
            "port"        => $port,
            "unix_socket" => $socket,
            "dbname"      => $dbName,
            "charset"     => $charset
        ]));
    }
}
