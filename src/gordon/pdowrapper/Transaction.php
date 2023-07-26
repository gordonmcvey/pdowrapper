<?php

declare(strict_types=1);

namespace gordon\pdowrapper;

/**
 * Problems that this has to solve:
 *
 * * Maintain list of queries that have been/weil be executed as part of a transaction
 * * Remember the last query executed
 * * Allow for the entire collection of queries to be run as an atomic unit on the database server
 *     * Management of dependencies between queries (If query B depends on the result of query A then the result of
 *       the last execution of query A needs to be made available in a way query B can refer to
 * * Allow for replay of the last query/transaction in the event of a transient error
 * * Allow for retrieval of relevant results for the consuming process
 * * Do some sort of checks for things that can't be run inside transactions? (eg DDL commands on MySQL implicitly commit
 *   any open transactions, and it will behave as if not in a transaction if working with storage engines that don't
 *   support transactions).  This may be a bit too much of a stretch though, and we probably should just rely on the
 *   developer using this to know what they can and can't run inside a transaction
 *
 * @package gordon\pdowrapper
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class Transaction
{
    /**
     * @var array<PDOStatement>
     */
    private array $queries = [];

    /**
     * @param PDOStatement $statement
     * @return $this
     */
    public function add(PDOStatement $statement): static
    {
        $this->queries[] = $statement;
        return $this;
    }

    public function run(): static
    {
        foreach ($this->queries as $query) {

        }
        return $this;
    }
}
