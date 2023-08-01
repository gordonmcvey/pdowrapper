<?php

declare(strict_types=1);

namespace gordon\pdowrapper\factory;

use gordon\pdowrapper\ConnectionSpec;
use gordon\pdowrapper\interface\factory\IConnectionFactory;
use PDO;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 *
 * @package gordon\pdowrapper\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class ConnectionFactory implements IConnectionFactory, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(private readonly ConnectionSpec $spec)
    {
    }

    /**
     * @inheritDoc
     *
     * This method assumes that the PDO class constructor is identical to the stock PDO class.
     */
    public function get(): PDO
    {
        $this->logger?->debug(sprintf("%s: Connecting to DSN %s", __METHOD__, $this->spec->dsn));
        $pdo = new $this->spec->pdoClass(
            (string) $this->spec->dsn,
            $this->spec->userName,
            $this->spec->password,
            $this->spec->options
        );

        if ($pdo instanceof LoggerAwareInterface) {
            $pdo->setLogger($this->logger);
        }

        return $pdo;
    }
}
