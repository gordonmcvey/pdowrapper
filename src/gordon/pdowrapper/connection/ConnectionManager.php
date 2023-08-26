<?php

declare(strict_types=1);

namespace gordon\pdowrapper\connection;

use DomainException;
use gordon\pdowrapper\interface\connection\IConnectionManager;
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
class ConnectionManager implements IConnectionManager, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * List of PDO attributes that the user is not allowed to override
     */
    private const FIXED_ATTRIBS = [
        PDO::ATTR_EMULATE_PREPARES  => false,
        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ];

    /**
     * List of default values for PDO attributes
     */
    private const DEFAULT_ATTRIBS = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ];

    /**
     * The connection being managed by the manager
     *
     * @var PDO|null
     */
    private ?PDO $connection = null;

    /**
     * Set of attributes that will be applied to new connections via setAttribute()
     *
     * @var array<int, mixed>
     */
    private array $attributes;

    /**
     * @param IConnectionFactory $connectionFactory The factory that will handle PDO instantiation
     */
    public function __construct(private readonly IConnectionFactory $connectionFactory)
    {
        $this->attributes = array_replace(self::DEFAULT_ATTRIBS, self::FIXED_ATTRIBS,);
    }

    /**
     * @inheritDoc
     */
    public function getConnection(): PDO
    {
        if (null === $this->connection) {
            $this->initConnection();
        } else {
            $this->logger?->debug(sprintf("%s: Connection already established, reusing reference", __METHOD__));
        }
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function getConnectionIfConnected(): ?PDO
    {
        return $this->connection;
    }

    /**
     * NOTE: Due to the way the underlying PDO classes work, calling this method is not guaranteed to cause the
     * connection that the connection manager's PDO instance represents to close.  It merely unsets the reference to
     * the instance itself.  If there are still PDOStatements that were prepared from this PDO instance in scope then
     * these statements will hold the connection open until they are unset too.
     *
     * @inheritDoc
     */
    public function expireConnection(): self
    {
        $this->connection = null;
        return $this;
    }

    /**
     * NOTE: A false is not an explicit guarantee that the connection established by this connection manager isn't still
     * active.  Due to how PDO is implemented the connection remains open so long as there are PDOStatement objects
     * in scope, even if the PDO instance itself has gone out of scope and would otherwise be GC'd.
     *
     * @inheritDoc
     */
    public function isConnected(): bool
    {
        return null !== $this->connection;
    }

    /**
     * @inheritDoc
     * @throws DomainException
     */
    public function setAttribute(int $attribute, mixed $value): bool
    {
        if (isset(self::FIXED_ATTRIBS[$attribute])) {
            throw new DomainException("You cannot override attribute $attribute");
        }

        $this->attributes[$attribute] = $value;
        return $this->connection?->setAttribute($attribute, $value) ?? true;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(int $attribute): mixed
    {
        // If we're connected then ensure our local cache reflects the reality of the connection's attributes
        if (!isset($this->attributes[$attribute]) && $this->connection) {
            $this->attributes[$attribute] = $this->connection->getAttribute($attribute);
        }

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
