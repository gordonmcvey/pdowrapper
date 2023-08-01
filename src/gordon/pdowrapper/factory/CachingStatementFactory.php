<?php

declare(strict_types=1);

namespace gordon\pdowrapper\factory;

use PDOStatement;

/**
 * CachingStatementFactory class
 *
 * This extended factory maintains a cache of previously prepared PDOStatements.  If a request ia made for a
 * PDOStatement that already exists in the cache, then the cached version is returned.  This reduces both the number of
 * statements prepared, which in turn should lead to some performance improvement.
 *
 * @package gordon\pdowrapper\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class CachingStatementFactory extends StatementFactory
{
    /*
     * This hashing algorithm has been selected for speed, as we want this method to add as little overhead as
     * practical.  As this algorithm is optimised for speed rather than low probability of collisions we may need to
     * look into additional measures to avoid two completely different SQL strings colliding and the wrong statement
     * being returned, such as bucketing.
     *
     * As this still seems unlikely we're just using the hash as a direct index for now but we may want to revisit this
     * if it turns out to be a problem.
     */
    private const HASH_ALGO = "xxh3";

    /**
     * Cache of prepared statements, keyed by a hash of the SQL query embodied by the statement
     *
     * We may want to switch to a weak map for this
     *
     * @var array<string, PDOStatement>
     */
    private array $statementCache = [];

    /**
     * @inheritDoc
     */
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        /*
         * Normalise and hash the given SQL query string
         *
         * We aren't doing a significant amount of normalisation in the interests of speed.  We may want to do a bit
         * more normalisation here such as stripping whitespace, but the goal is to be fast rather than completely
         * eliminate the possibility of functionally equivalent queries getting prepared.  Short of parsing the full
         * query it would be impossible to 100% prevent that from happening anyway
         */
        $hash = hash(self::HASH_ALGO, strtoupper(trim($query)));

        if (!isset($this->statementCache[$hash])) {
            $this->logger?->debug(sprintf("%s: No statement found for hash '%s'", __METHOD__, $hash));
            $this->statementCache[$hash] = parent::prepare($query, $options);
        }
        $this->logger?->debug(sprintf("%s: Returning cached statement for hash '%s'", __METHOD__, $hash));

        return $this->statementCache[$hash];
    }

    /**
     * Flush the statement cache
     *
     * @return $this
     */
    public function flush(): self
    {
        $this->statementCache = [];
        return $this;
    }
}
