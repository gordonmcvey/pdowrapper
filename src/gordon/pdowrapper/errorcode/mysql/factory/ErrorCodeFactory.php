<?php

declare(strict_types=1);

namespace gordon\pdowrapper\errorcode\mysql\factory;

use gordon\pdowrapper\errorcode\mysql\ClientError;
use gordon\pdowrapper\errorcode\mysql\GlobalError;
use gordon\pdowrapper\errorcode\mysql\ServerError;
use gordon\pdowrapper\interface\errorcode\factory\IErrorCodeFactory;
use gordon\pdowrapper\interface\errorcode\mysql\IErrorCodeEnum;
use PDOException;

/**
 *
 * @package gordon\pdowrapper\errorcode\mysql\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
final readonly class ErrorCodeFactory implements IErrorCodeFactory
{
    /**
     * @inheritDoc
     */
    public function fromException(PDOException $e): IErrorCodeEnum
    {
        return $this->fromCode($e->errorInfo[1]);
    }

    /**
     * @inheritDoc
     */
    public function fromCode(int|string $code): IErrorCodeEnum
    {
        // The MySQL PDO driver can encode the error code as a string or an int.  All the associated enums use strings
        // for consistency, so we'll cast the given code to string to eliminate the possibility of a type error
        $code = (string) $code;

        if (null !== ($enum = ClientError::tryFrom($code))) {
            return $enum;
        }
        if (null !== ($enum = ServerError::tryFrom($code))) {
            return $enum;
        }
        return GlobalError::from($code);
    }
}
