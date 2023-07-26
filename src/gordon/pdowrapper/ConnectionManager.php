<?php

declare(strict_types=1);

namespace gordon\pdowrapper;

use gordon\pdowrapper\interface\factory\IConnectionFactory;
use PDO;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class ConnectionManager
 *
 * Class responsibilities: maintain and configure the backend PDO object
 *
 * @package gordon\pdowrapper
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class ConnectionManager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * The connection being managed by the manager
     *
     * @var PDO|null
     */
    private ?PDO $connection = null;

    private array $attributes = [];

    /**
     * @param IConnectionFactory $connectionFactory The class that will handle PDO instantiation
     */
    public function __construct(private readonly IConnectionFactory $connectionFactory) {}

    /**
     * Return the PDO object that's actually communicating with the database, or instantiate it if the connection is not
     * established
     *
     * @param array|null $attributes
     * @return PDO
     */
    public function getConnection(?array $attributes = []): PDO
    {
        if (null === $this->connection) {
            $this->initConnection($attributes);
        } else {
            $this->logger?->debug(sprintf("%s: Connection already established, reusing reference", __METHOD__));
        }
        return $this->connection;
    }

    /**
     * Return the PDO object that's actually communicating with the database, or null it if the connection is not
     * established
     *
     * @return PDO|null
     */
    public function getConnectionIfConnected(): ?PDO
    {
        return $this->connection;
    }

    /**
     * Expire the currently opened connection
     *
     * NOTE: Due to the way the underlying PDO classes work, calling this method is not guaranteed to cause the
     * connection that the connection manager's PDO instance represents to close.  It merely unsets the reference to
     * the instance itself.  If there are still PDOStatements that were prepared from this PDO instance in scope then
     * these statements will hold the connection open until they are unset too.
     *
     * @return $this
     */
    public function expireConnection(): self
    {
        $this->connection = null;
        return $this;
    }

    /**
     * Check if the connection manager's PDO object is active
     *
     * NOTE: A false is not an explicit guarantee that the connection established by this connection manager isn't still
     * active.  Due to how PDO is implemented the connection remains open so long as there are PDOStatement objects
     * in scope, even if the PDO instance itself has gone out of scope and would otherwise be GC'd.
     *
     * @return bool
     */
    public function isConnected(): bool
    {
        return null !== $this->connection;
    }

    public function setAttribute(int $attribute, mixed $value): bool
    {
        $this->attributes[$attribute] = $value;
        return $this->connection?->setAttribute($attribute, $value) ?? true;
    }

    public function getAttribute(int $attribute): mixed
    {
        return $this->connection?->getAttribute($attribute) ?? $this->attributes[$attribute] ?? null;
    }

    /**
     * @return void
     */
    private function initConnection(): void
    {
        $this->logger?->debug(sprintf("%s: Establishing connection", __METHOD__));
        $this->connection = $this->connectionFactory->get();
        $this->applyAttributes();
    }

    /**
     * @return void
     */
    private function applyAttributes(): void
    {
        foreach ($this->attributes as $attrKey => $attribute) {
            $this->connection->setAttribute($attrKey, $attribute);
        }
    }
}
