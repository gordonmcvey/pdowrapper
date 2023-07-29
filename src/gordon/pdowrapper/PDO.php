<?php

declare(strict_types=1);

namespace gordon\pdowrapper;

use gordon\pdowrapper\exception\PDOException;
use gordon\pdowrapper\interface\factory\IStatementFactory;
use PDO as RealPDO;
use PDOException as BasePDOException;
use PDOStatement;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use ValueError;

/**
 * Class PDO
 *
 * Class responsibilities: Provide a front-end for the PDO wrapper that can be used as a drop-in replacement for a regular
 * PDO instance
 *
 * @package gordon\pdowrapper
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo Ensure that PDO::ATTR_ERRMODE cannot be set to anything other than PDO::ERRMODE_EXCEPTION
 */
class PDO extends RealPDO implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @param ConnectionManager $connectionManager
     * @param IStatementFactory $statementFactory
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(
        private readonly ConnectionManager $connectionManager,
        private readonly IStatementFactory $statementFactory
    )
    {
    }

    /**
     * @inheritDoc
     * @throws PDOException
     * @throws ValueError
     */
    public function query($query, $fetchMode = null, ...$fetch_mode_args): PDOStatement|false
    {
        $preparedStatement = $this->prepare($query);
        $preparedStatement->setFetchMode($fetchMode ?? RealPDO::FETCH_DEFAULT, ...$fetch_mode_args);
        $preparedStatement->execute();
        return $preparedStatement;
    }

    /**
     * @inheritDoc
     * @throws PDOException
     * @throws ValueError
     */
    public function exec(string $statement): int|false
    {
        $preparedStatement = $this->prepare($statement);
        $preparedStatement->execute();
        return $preparedStatement->rowCount();
    }

    /**
     * @inheritDoc
     * @throws PDOException
     */
    public function lastInsertId(?string $name = null): string|false
    {
        try {
            return $this->connectionManager->getConnectionIfConnected()?->lastInsertId($name) ?? false;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     * @throws ValueError
     */
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        return $this->statementFactory->prepare($query, $options);
    }

    /**
     * @inheritDoc
     * @todo This should start a list of statements to be executed when commit is called
     */
    public function beginTransaction(): bool
    {
        try {
            return $this->connectionManager->getConnection()->beginTransaction();
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     * @todo This should execute the previously specified list of statements
     * @todo Implement transaction replay for failures where replay is appropriate
     */
    public function commit(): bool
    {
        try {
            return $this->connectionManager->getConnection()->commit();
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     * @todo This should only execute when connected and in a transaction, otherwise it should just clear the statement list
     */
    public function rollBack(): bool
    {
        try {
            return $this->connectionManager->getConnection()->rollBack();
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     * @todo Should return true if we're in the process of building a transaction prior to actually connecting to the DB
     */
    public function inTransaction(): bool
    {
        return $this->connectionManager->getConnectionIfConnected()?->inTransaction() ?? false;
    }

    /**
     * @inheritDoc
     */
    public function errorCode(): ?string
    {
        return $this->connectionManager->getConnectionIfConnected()?->errorCode() ?? null;
    }

    /**
     * @inheritDoc
     */
    public function errorInfo(): array
    {
        return $this->connectionManager->getConnectionIfConnected()?->errorInfo() ?? ["00000", null, null];
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(int $attribute): mixed
    {
        return $this->connectionManager->getAttribute($attribute);
    }

    /**
     * @inheritDoc
     */
    public function setAttribute(int $attribute, mixed $value): bool
    {
        return $this->connectionManager->setAttribute($attribute, $value);
    }

    /**
     * @inheritDoc
     *
     * We don't recommend using this method as it will instantiate a real connection if one hasn't been established yet
     * just to quote a string.  We also don't recommend using this for building queries from external input as there is
     * always a risk that the quoting won't be perfect to protect from things such as SQL injections, and not all PDO
     * drivers implement it anyway.  There are already better approaches available such as prepared statements for
     * handling string quoting.
     */
    public function quote(string $string, int $type = \PDO::PARAM_STR): string|false
    {
        return $this->connectionManager->getConnection()->quote($string, $type);
    }

    /*
     * The following methods are specific to the CUBRID driver
     */

    /**
     * @inheritDoc
     */
    public function cubrid_schema(int $schema_type, ?string $table_name, ?string $col_name): array
    {
        return $this->connectionManager->getConnection()->cubrid_schema($schema_type, $table_name, $col_name);
    }

    /*
     * The following methods are specific to the SQLite driver
     */

    /**
     * @inheritDoc
     *
     * We STRONGLY discourage use of this method as it's marked as experimental in the PHP documentation, and honestly
     * I have no idea what they were even thinking to include a method specific to a particular DB in what's supposed to
     * be a generic DB interface class.  It's only included here for completeness
     */
    public function sqliteCreateAggregate(
        string   $function_name,
        callable $step_func,
        callable $finalize_func,
        ?int     $num_args = 0
    ): bool
    {
        return $this->connectionManager->getConnection()->sqliteCreateAggregate($function_name, $step_func, $finalize_func, $num_args);
    }

    /**
     * @inheritDoc
     *
     * We STRONGLY discourage use of this method as it's marked as experimental in the PHP documentation, and honestly
     * I have no idea what they were even thinking to include a method specific to a particular DB in what's supposed to
     * be a generic DB interface class.  It's only included here for completeness
     */
    public function sqliteCreateCollation(string $name, callable $callback): bool
    {
        return $this->connectionManager->getConnection()->sqliteCreateCollation($name, $callback);
    }

    /**
     * @inheritDoc
     *
     * We STRONGLY discourage use of this method as it's marked as experimental in the PHP documentation, and honestly
     * I have no idea what they were even thinking to include a method specific to a particular DB in what's supposed to
     * be a generic DB interface class.  It's only included here for completeness
     */
    public function sqliteCreateFunction($function_name, $callback, $num_args = -1, $flags = 0): bool
    {
        return $this->connectionManager->getConnection()->sqliteCreateFunction($function_name, $callback, $num_args, $flags);
    }

    /*
     * The following methods are all specific to the PostgreSQL driver
     */

    /**
     * @inheritDoc
     */
    public function pgsqlCopyFromArray(string $tableName, array $rows, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null): bool
    {
        return $this->connectionManager->getConnection()->pgsqlCopyFromArray($tableName, $rows, $separator, $nullAs, $fields);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlCopyFromFile(string $tableName, string $filename, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null): bool
    {
        return $this->connectionManager->getConnection()->pgsqlCopyFromFile($tableName, $filename, $separator, $nullAs, $fields);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlCopyToArray(string $tableName, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null): array|false
    {
        return $this->connectionManager->getConnection()->pgsqlCopyToArray($tableName, $separator, $nullAs, $fields);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlCopyToFile(string $tableName, string $filename, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null): bool
    {
        return $this->connectionManager->getConnection()->pgsqlCopyToFile($tableName, $filename, $separator, $nullAs, $fields);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlLOBCreate(): string|false
    {
        return $this->connectionManager->getConnection()->pgsqlLOBCreate();
    }

    /**
     * @inheritDoc
     */
    public function pgsqlLOBOpen(string $oid, string $mode = "rb")
    {
        return $this->connectionManager->getConnection()->pgsqlLOBOpen($oid, $mode);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlLOBUnlink(string $oid): bool
    {
        return $this->connectionManager->getConnection()->pgsqlLOBUnlink($oid);
    }

    /**
     * @inheritDoc
     */
    public function pgsqlGetNotify(int $fetchMode = \PDO::FETCH_DEFAULT, int $timeoutMilliseconds = 0): array|false
    {
        return $this->connectionManager->getConnectionIfConnected()?->pgsqlGetNotify($fetchMode, $timeoutMilliseconds) ?? false;
    }

    /**
     * @inheritDoc
     */
    public function pgsqlGetPid(): int
    {
        return $this->connectionManager->getConnectionIfConnected()?->pgsqlGetPid() ?? 0;
    }
}
