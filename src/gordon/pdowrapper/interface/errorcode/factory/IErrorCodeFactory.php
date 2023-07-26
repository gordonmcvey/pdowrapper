<?php

declare(strict_types=1);

namespace gordon\pdowrapper\interface\errorcode\factory;

use gordon\pdowrapper\interface\errorcode\IErrorCodeEnum;
use PDOException;
use ValueError;

/**
 *
 * @package gordon\pdowrapper\interface\errorcode\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface IErrorCodeFactory
{
    /**
     * @param PDOException $e
     * @return IErrorCodeEnum
     * @throws ValueError
     */
    public function fromException(PDOException $e): IErrorCodeEnum;

    /**
     * @param int|string $code
     * @return IErrorCodeEnum
     * @throws ValueError
     */
    public function fromCode(int|string $code): IErrorCodeEnum;
}
