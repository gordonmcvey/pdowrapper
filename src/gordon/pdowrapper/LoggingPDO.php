<?php

declare(strict_types=1);

namespace gordon\pdowrapper;

use PDO;
use PDOStatement;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class LoggingPDO
 *
 * NOTE: This class isn't really intended for production, it's largely intended for debugging at which point the wrapper
 * is calling methods on an actual PDO connection and when the connection is garbage collected.
 *
 * @package gordon\pdowrapper
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class LoggingPDO extends \PDO implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @inheritDoc
     */
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->logger?->debug(__METHOD__);
        return parent::prepare($query, $options);
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::commit();
    }

    /**
     * @inheritDoc
     */
    public function rollBack(): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::rollBack();
    }

    /**
     * @inheritDoc
     */
    public function inTransaction(): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::inTransaction();
    }

    /**
     * @inheritDoc
     */
    public function setAttribute(int $attribute, mixed $value): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::setAttribute($attribute, $value);
    }

    /**
     * @inheritDoc
     */
    public function exec(string $statement): int|false
    {
        $this->logger?->debug(__METHOD__);
        return parent::exec($statement);
    }

    /**
     * @inheritDoc
     */
    public function query(string $query, ?int $fetchMode = null, ...$fetch_mode_args): PDOStatement|false
    {
        $this->logger?->debug(__METHOD__);
        return parent::query($query, $fetchMode, ...$fetch_mode_args);
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(?string $name = null): string|false
    {
        $this->logger?->debug(__METHOD__);
        return parent::lastInsertId($name);
    }

    /**
     * @inheritDoc
     */
    public function errorCode(): ?string
    {
        $this->logger?->debug(__METHOD__);
        return parent::errorCode();
    }

    /**
     * @inheritDoc
     */
    public function errorInfo(): array
    {
        $this->logger?->debug(__METHOD__);
        return parent::errorInfo();
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(int $attribute): mixed
    {
        $this->logger?->debug(__METHOD__);
        return parent::getAttribute($attribute);
    }

    /**
     * @inheritDoc
     */
    public function quote(string $string, int $type = PDO::PARAM_STR): string|false
    {
        $this->logger?->debug(__METHOD__);
        return parent::quote($string, $type);
    }

    /**
     * @inheritDoc
     */
    public function sqliteCreateFunction($function_name, $callback, $num_args = -1, $flags = 0)
    {
        $this->logger?->debug(__METHOD__);
        return parent::sqliteCreateFunction($function_name, $callback, $num_args, $flags);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlCopyFromArray(string $tableName, array $rows, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlCopyFromArray($tableName, $rows, $separator, $nullAs, $fields);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlCopyFromFile(string $tableName, string $filename, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlCopyFromFile($tableName, $filename, $separator, $nullAs, $fields);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlCopyToArray(string $tableName, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null): array|false
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlCopyToArray($tableName, $separator, $nullAs, $fields);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlCopyToFile(string $tableName, string $filename, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlCopyToFile($tableName, $filename, $separator, $nullAs, $fields);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlLOBCreate(): string|false
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlLOBCreate();
    }

    /**
     * @inheritDoc
     */
    public function pgsqlLOBOpen(string $oid, string $mode = "rb")
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlLOBOpen($oid, $mode);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlLOBUnlink(string $oid): bool
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlLOBUnlink($oid);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlGetNotify(int $fetchMode = PDO::FETCH_DEFAULT, int $timeoutMilliseconds = 0): array|false
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlGetNotify($fetchMode, $timeoutMilliseconds);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlGetPid(): int
    {
        $this->logger?->debug(__METHOD__);
        return parent::pgsqlGetPid();
    }

    /**
     * Announce that the PDO is being destroyed
     */
    public function __destruct()
    {
        $this->logger?->debug(__METHOD__ . ": PDO instance fell out of scope and is being GC'd");
    }
}
