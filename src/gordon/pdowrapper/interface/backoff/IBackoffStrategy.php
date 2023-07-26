<?php

declare(strict_types=1);

namespace gordon\pdowrapper\interface\backoff;

/**
 *
 * @package gordon\pdowrapper\interface\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface IBackoffStrategy
{
    /**
     * Determine how much backoff should be applied before an operation is re-attempted.  The returned value is an
     * integer value in microseconds
     *
     * @return int
     */
    public function backoff(): int;
}
