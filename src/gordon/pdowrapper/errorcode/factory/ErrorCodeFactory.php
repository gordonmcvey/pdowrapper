<?php

declare(strict_types=1);

namespace gordon\pdowrapper\errorcode\factory;

use gordon\pdowrapper\interface\errorcode\factory\IErrorCodeFactory;
use gordon\pdowrapper\interface\errorcode\IErrorCodeEnum;
use http\Exception\RuntimeException;
use PDOException;
use ValueError;

/**
 *
 * @package gordon\pdowrapper\errorcode\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
final class ErrorCodeFactory implements IErrorCodeFactory
{
    private const DRIVER_TEMPLATE = "\\gordon\\pdowrapper\\errorcode\\%s\\factory\\ErrorCodeFactory";

    private IErrorCodeFactory $instance;

    /**
     * @param string $dbType
     */
    public function __construct(string $dbType)
    {
        if (empty($dbType)) {
            throw new ValueError("Database Type can't be empty");
        }

        $className = sprintf(self::DRIVER_TEMPLATE, strtolower($dbType));
        $instance = new $className();
        if (!$instance instanceof IErrorCodeFactory) {
            // @todo Better error handling
            throw new RuntimeException("Instantiated class doesn't implement IErrorCodeFactory");
        }
        $this->instance = $instance;
    }

    /**
     * @inheritDoc
     */
    public function fromException(PDOException $e): IErrorCodeEnum
    {
        return $this->instance->fromException($e);
    }

    /**
     * @inheritDoc
     */
    public function fromCode(int|string $code): IErrorCodeEnum
    {
        return $this->instance->fromCode($code);
    }
}
