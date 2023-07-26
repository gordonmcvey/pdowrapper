<?php

declare(strict_types=1);

namespace gordon\pdowrapper;

use gordon\pdowrapper\exception\PDOException;
use Iterator;
use JetBrains\PhpStorm\ArrayShape;
use PDO;
use PDOException as BasePDOException;
use PDOStatement as RealPDOStatement;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use ValueError;

/**
 * Class PDOStatement
 *
 * This class serves as a proxy for PDOStatement.  This allows us to defer statement instantiation until its actually
 * executed.
 *
 * @package gordon\pdowrapper
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo Ensure that PDO::ATTR_ERRMODE cannot be set to anything other than PDO::ERRMODE_EXCEPTION
 */
class PDOStatement extends RealPDOStatement implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ?RealPDOStatement $statement = null;

    /**
     * Fetch mode that will be applied to the statement via setFetchMode()
     *
     * @var int
     */
    private int $fetchMode = PDO::FETCH_DEFAULT;

    /**
     * Additional parameters for setFetchMode()
     *
     * As this is intended to be used as variable-length parameter list for when the real setFetchMode() is called we
     * can make no type hints about it other than it being an array.
     *
     * @var array
     */
    private array $fetchParams = [];

    /**
     * @var array<int, mixed>
     */
    private array $attributes = [];

    /**
     * Columns that will be bound to the statement via bindColumn()
     *
     * @var array<int|string, array{
     *     column:int|string,
     *     var:mixed,
     *     type:int|null,
     *     maxLength: int|null,
     *     driverOptions: mixed|null
     * }>
     */
    private array $boundColumns = [];

    /**
     * Parameters that will be bound to the statement via bindParam()
     *
     * @var array<int|string, array{
     *     param:int|string,
     *     var:mixed,
     *     type:int|null,
     *     maxLength: int|null,
     *     driverOptions: mixed|null
     * }>
     */
    private array $boundParams = [];

    /**
     * Values that will be bound to the statement via bindValue()
     *
     * @var array<int|string, array{
     *     param:int|string,
     *     value:mixed,
     *     type:int|null
     * }>
     */
    private array $boundValues = [];

    /**
     * @param ConnectionManager $connectionManager Must return a \PDO instance and not a wrapper, or we risk an infinite recursion
     * @param string $query The query that will be prepared when instantiating the proxied statement
     * @param array<PDO::ATTR_*, int> $options The options that will be used when instantiating the proxied statement
     */
    public function __construct(
        private readonly ConnectionManager $connectionManager,
        public readonly string             $query,
        private readonly array             $options = []
    ) {
        // Emulate the behaviour of PDO::prepare() if called with an empty query string
        if (empty($this->query)) {
            throw new ValueError(__METHOD__ . "(): Argument #2 (\$query) cannot be empty");
        }
    }

    /**
     * @inheritDoc
     * @throws PDOException
     */
    public function execute(?array $params = null): bool
    {
        if (null === $this->statement) {
            // It's time to instantiate the real PDOStatement
            $this->initStatement();
        } else {
            // The statement is already prepared so there's nothing to do
            $this->logger?->debug(sprintf("%s: query already prepared: %.32s", __METHOD__, $this->query));
        }
        try {
            return $this->statement->execute($params);
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function fetch(int $mode = PDO::FETCH_BOTH, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        try {
            return $this->statement?->fetch($mode, $cursorOrientation, $cursorOffset) ?? false;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function bindParam(int|string $param, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = null, mixed $driverOptions = null): bool
    {
        $this->boundParams[$param]  = [
            "param"         => $param,
            "var"           => &$var,
            "type"          => $type,
            "maxLength"     => $maxLength,
            "driverOptions" => $driverOptions,
        ];
        try {
            return $this->statement?->bindParam($param, $var, $type, $maxLength, $driverOptions) ?? true;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function bindColumn(int|string $column, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = null, mixed $driverOptions = null): bool
    {
        $this->boundColumns[$column] = [
            "column"        => $column,
            "var"           => &$var,
            "type"          => $type,
            "macLength"     => $maxLength,
            "driverOptions" => $driverOptions,
        ];
        try {
            return $this->statement?->bindColumn($column, $var, $type, $maxLength, $driverOptions);
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function bindValue(int|string $param, mixed $value, int $type = PDO::PARAM_STR): bool
    {
        $this->boundValues[$param] = [
            "param" => $param,
            "value" => $value,
            "type"  => $type,
        ];
        try {
            return $this->statement?->bindValue($param, $value, $type) ?? true;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function rowCount(): int
    {
        try {
            return $this->statement?->rowCount() ?? 0;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function fetchColumn(int $column = 0): mixed
    {
        try {
            return $this->statement?->fetchColumn($column) ?? false;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function fetchAll(int $mode = PDO::FETCH_BOTH, ...$args): array
    {
        try {
            return $this->statement?->fetchAll($mode, ...$args) ?? [];
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function fetchObject(?string $class = "stdClass", array $constructorArgs = []): object|false
    {
        try {
            return $this->statement?->fetchObject($class, $constructorArgs) ?? false;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function errorCode(): ?string
    {
        return $this->statement?->errorCode() ?? "00000";
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([0 => "string", 1 => "int", 2 => "string"])]
    public function errorInfo(): array
    {
        return $this->statement?->errorInfo() ?? ["00000", null, null];
    }

    /**
     * @inheritDoc
     */
    public function setAttribute(int $attribute, mixed $value): bool
    {
        $this->attributes[$attribute] = $value;
        return $this->statement?->setAttribute($attribute, $this->attributes[$attribute]) ?? true;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(int $name): mixed
    {
        return $this->statement?->getAttribute($name) ?? $this->attributes[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function columnCount(): int
    {
        return $this->statement?->columnCount() ?? 0;
    }

    /**
     * @inheritDoc
     * @todo Implement getColumnMeta
     */
    #[ArrayShape([
        "name"          => "string",
        "len"           => "int",
        "precision"     => "int",
        "oci:decl_type" => "int|string",
        "native_type"   => "string",
        "scale"         => "int",
        "flags"         => "array",
        "pdo_type"      => "int"
    ])] public function getColumnMeta(int $column): array|false
    {
        return $this->statement?->getColumnMeta($column) ?? false;
    }

    /**
     * @inheritDoc
     */
    public function setFetchMode($mode, $className = null, ...$params): void
    {
        $this->fetchMode = $mode;
        $this->fetchParams = $params;
        $this->statement?->setFetchMode($this->fetchMode, ...$this->fetchParams);
    }

    /**
     * @inheritDoc
     */
    public function nextRowset(): bool
    {
        return $this->statement?->nextRowset() ?? false;
    }

    /**
     * @inheritDoc
     */
    public function closeCursor(): bool
    {
        try {
            return $this->statement?->closeCursor() ?? false;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function debugDumpParams(): ?bool
    {
        return $this->statement?->debugDumpParams() ?? null;
    }

    /**
     * @inheritDoc
     *
     * NOTE: Calling this method triggers statement initialisation if the statement has not yet been initialised (which
     * in turn may also trigger database initialisation if the database connection hasn't been established yet)
     */
    public function getIterator(): Iterator
    {
        if (null === $this->statement) {
            $this->initStatement();
        }
        return $this->statement->getIterator();
    }

    /**
     * Instantiate and configure the real PDOStatement that this class proxies for
     *
     * @return void
     * @throws PDOException
     */
    private function initStatement(): void
    {
        $this->logger?->debug(sprintf("%s: Preparing and configuring query: %.32s", __METHOD__, $this->query));
        try {
            $statement = $this->connectionManager->getConnection()->prepare($this->query, $this->options);

            // Ensure the generated statement has been properly configured
            $statement->setFetchMode($this->fetchMode, ...$this->fetchParams);
            foreach ($this->attributes as $attrKey => $attrValue) {
                $statement->setAttribute($attrKey, $attrValue);
            }

            foreach ($this->boundColumns as $column) {
                $statement->bindColumn($column["column"], $column["var"], $column["type"], $column["maxLength"], $column["driverOptions"]);
            }

            foreach ($this->boundParams as $param) {
                $statement->bindParam($param["param"], $param["var"], $param["type"], $param["maxLength"], $param["driverOptions"]);
            }

            foreach ($this->boundValues as $value) {
                $statement->bindValue($value["param"], $value["value"], $value["type"]);
            }
            $this->statement = $statement;
        } catch (BasePDOException $e) {
            throw PDOException::fromException($e);
        }
    }
}
