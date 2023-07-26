<?php

declare(strict_types=1);

namespace gordon\pdowrapper\interface\errorcode\sqlite;

use gordon\pdowrapper\interface\errorcode\IErrorCodeEnum as BaseErrorCodeEnum;

/**
 * ErrorCodeEnum
 *
 * This interface exists for cases when we need to restrict the error code enum that can be used to ones that are
 * SQLite compatible
 *
 * @package gordon\pdowrapper\interface\errorcode\sqlite
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface IErrorCodeEnum extends BaseErrorCodeEnum
{
}
