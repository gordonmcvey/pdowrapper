<?php

namespace gordon\pdowrapper\errorcode\sqlite\factory;

use gordon\pdowrapper\errorcode\sqlite\Error;
use gordon\pdowrapper\interface\errorcode\factory\IErrorCodeFactory;
use gordon\pdowrapper\interface\errorcode\IErrorCodeEnum;
use PDOException;

/**
 *
 * @package gordon\pdowrapper\errorcode\sqlite\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
final readonly class ErrorCodeFactory implements IErrorCodeFactory
{
    /**
     * @inheritDoc
     */
    public function fromException(PDOException $e): IErrorCodeEnum
    {
        return Error::from($e->errorInfo[1]);
    }

    /**
     * @inheritDoc
     */
    public function fromCode(int|string $code): IErrorCodeEnum
    {
        return Error::from($code);
    }
}
