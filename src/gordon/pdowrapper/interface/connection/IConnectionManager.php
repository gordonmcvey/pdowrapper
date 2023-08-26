<?php

declare(strict_types=1);

namespace gordon\pdowrapper\interface\connection;

use PDO;

/**
 * IConnectionManager interface
 *
 * @package gordon\pdowrapper\interface\connection
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface IConnectionManager
{
    /**
     * Return the PDO object that's actually communicating with the database, or instantiate it if the connection is not
     * established
     *
     * @return PDO
     */
    public function getConnection(): PDO;

    /**
     * Return the PDO object that's actually communicating with the database, or null it if the connection is not
     * established
     *
     * @return PDO|null
     */
    public function getConnectionIfConnected(): ?PDO;

    /**
     * Expire the currently opened connection
     *
     * @return $this
     */
    public function expireConnection(): self;

    /**
     * Check if the connection manager's PDO object is active
     *
     * @return bool
     */
    public function isConnected(): bool;

    /**
     * @param int $attribute
     * @param mixed $value
     * @return bool
     */
    public function setAttribute(int $attribute, mixed $value): bool;

    /**
     * @param int $attribute
     * @return mixed
     */
    public function getAttribute(int $attribute): mixed;
}
