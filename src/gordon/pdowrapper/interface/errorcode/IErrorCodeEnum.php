<?php

namespace gordon\pdowrapper\interface\errorcode;

/**
 *
 * @package gordon\pdowrapper\interface\errorcode
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface IErrorCodeEnum extends \BackedEnum
{
    /**
     * Your implementing enum should put a list of codes that can be ignored here in the form of an array where both the
     * key and the value is the ignorable code
     *
     * @var array <int|string, int|string>
     */
    public const IGNORABLE     = [];

    /**
     * Your implementing enum should put a list of codes that can be replayed here in the form of an array where both the
     * key and the value is the replayable code
     *
     * @var array <int|string, int|string>
     */
    public const REPLAYABLE    = [];

    /**
     * Your implementing enum should put a list of codes that allow reconnection attempts here in the form of an array
     * where both the key and the value is the reconnectable code
     *
     * @var array <int|string, int|string>
     */
    public const RECONNECTABLE = [];
}
