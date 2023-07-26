<?php

declare(strict_types=1);

namespace gordon\pdowrapper\errorcode;

use gordon\pdowrapper\interface\errorcode\ICodeClassifier;
use gordon\pdowrapper\interface\errorcode\IErrorCodeEnum;

/**
 * CodeClassifier
 *
 * @package gordon\pdowrapper\errorcode
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
readonly class CodeClassifier implements ICodeClassifier
{
    /**
     * @inheritDoc
     */
    public function canIgnore(IErrorCodeEnum $code): bool
    {
        return isset($code::IGNORABLE[$code->value]);
    }

    /**
     * @inheritDoc
     */
    public function canReplay(IErrorCodeEnum $code): bool
    {
        return isset($code::REPLAYABLE[$code->value]);
    }

    /**
     * @inheritDoc
     */
    public function canReconnect(IErrorCodeEnum $code): bool
    {
        return isset($code::RECONNECTABLE[$code->value]);
    }
}
