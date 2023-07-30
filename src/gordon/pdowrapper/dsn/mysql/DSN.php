<?php

declare(strict_types=1);

namespace gordon\pdowrapper\dsn\mysql;

use gordon\pdowrapper\dsn\DSN as AbstractDSN;
use ValueError;

final class DSN extends AbstractDSN
{
    private const DRIVER_PREFIX = "mysql";

    private const PORT_MIN = 1;

    private const PORT_MAX = 65535;

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

        if (null !== $port && ($port < self::PORT_MIN || $port > self::PORT_MAX)) {
            throw new ValueError(sprintf("Port must have a value between %d and %d", self::PORT_MIN, self::PORT_MAX));
        }

        // DSN initialisation
        parent::__construct(self::DRIVER_PREFIX . ":" . implode(";", array_filter([
            $host ? sprintf(self::ELEMENT_TEMPLATE, "host", $host) : null,
            $port ? sprintf(self::ELEMENT_TEMPLATE, "port", $port) : null,
            $socket ? sprintf(self::ELEMENT_TEMPLATE, "unix_socket", $socket) : null,
            $dbName ? sprintf(self::ELEMENT_TEMPLATE, "dbname", $dbName) : null,
            $charset ? sprintf(self::ELEMENT_TEMPLATE, "charset", $charset) : null,
        ])));
    }
}
