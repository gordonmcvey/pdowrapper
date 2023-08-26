<?php

declare(strict_types=1);

namespace gordon\pdowrapper\factory;

use gordon\pdowrapper\connection\ConnectionSpec;
use gordon\pdowrapper\exception\InstantiationException;
use gordon\pdowrapper\interface\factory\IConnectionFactory;
use PDO;
use PDOException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TypeError;

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
        try {
            $pdo = new $this->spec->pdoClass(
                (string) $this->spec->dsn,
                $this->spec->userName,
                $this->spec->password,
                $this->spec->options
            );
        } catch (PDOException $e) {
            throw InstantiationException::fromException($e);
        }

        // Protection against instantiating a non-PDO class
        if (!$pdo instanceof PDO) {
            throw new TypeError(sprintf("Object of class %s is not an instance of PDO", $pdo::class));
        }

        // Set logger if supported
        null !== $this->logger
            && $pdo instanceof LoggerAwareInterface
            && $pdo->setLogger($this->logger);

        return $pdo;
    }
}
