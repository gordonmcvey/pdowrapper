<?php

declare(strict_types=1);

namespace gordon\pdowrapper\interface\factory;

use PDOStatement;

/**
 *
 * @package gordon\pdowrapper\interface\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface IStatementFactory
{
    /**
     * Create a new prepared statement
     *
     * NOTE: The returned instance will be a subclass of the in-built PDOStatement with additional behaviour such as
     * lazy instantiation of the actual prepared statement.  It's type-hinted as PDOStatement for compatibility reasons
     *
     * @param string $query
     * @param array<\PDO::ATTR_*, int> $options
     * @return PDOStatement
     */
    public function prepare(string $query, array $options = []): PDOStatement;
}
