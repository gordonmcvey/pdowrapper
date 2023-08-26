<?php

declare(strict_types=1);

namespace gordon\pdowrapper\factory;

use gordon\pdowrapper\interface\connection\IConnectionManager;
use gordon\pdowrapper\interface\factory\IStatementFactory;
use gordon\pdowrapper\PDOStatement;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 *
 * @package gordon\pdowrapper\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class StatementFactory implements LoggerAwareInterface, IStatementFactory
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly IConnectionManager $connectionManager
    ) {
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query, array $options = []): PDOStatement
    {
        $this->logger?->debug(sprintf("%s Preparing query: '%.32s'", __METHOD__, $query));
        $stmt = new PDOStatement($this->connectionManager, $query, $options);
        if (null !== $this->logger) {
            $stmt->setLogger($this->logger);
        }
        return $stmt;
    }
}
