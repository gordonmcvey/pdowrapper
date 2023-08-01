<?php

declare(strict_types=1);

namespace gordon\pdowrapper\dsn\postgresql;

use gordon\pdowrapper\dsn\DSN as AbstractDSN;
use ValueError;

/**
 * DSN for PostgreSQL connections
 *
 * @package gordon\pdowrapper\dsn\mysql
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
final class DSN extends AbstractDSN
{
    private const DRIVER_PREFIX = "pgsql";

    /**
     * DSN constructor
     *
     * Note: The PostgreSQL DSN allows you to specify a username and a password, however this can also be specified via
     * the Connection Spec.  If user login details are specified in both places then the login details in the DSN take
     * precedence, with the values in the connection spec ignored.
     *
     * @param string $host Can specify either a domain name, or a UNIX socket
     * @param int|null $port
     * @param string|null $dbName
     * @param string|null $user
     * @param string|null $password
     * @param string|null $sslMode
     * @throws ValueError
     * @todo $sslmode is not well documented, maybe better implemented as an enum?
     */
    public function __construct(
        string  $host,
        ?int    $port = null,
        ?string $dbName = null,
        ?string $user = null,
        ?string $password = null,
        ?string $sslMode = null
    ) {
        // Input validation
        $this->validatePort($port);

        // DSN initialisation
        parent::__construct(self::DRIVER_PREFIX . ":" . $this->buildParamString([
            "host"     => $host,
            "port"     => $port,
            "dbname"   => $dbName,
            "user"     => $user,
            "password" => $password,
            "sslmode"  => $sslMode,
        ]));
    }
}
