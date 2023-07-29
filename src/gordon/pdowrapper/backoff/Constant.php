<?php

declare(strict_types=1);

namespace gordon\pdowrapper\backoff;

use gordon\pdowrapper\interface\backoff\IBackoffStrategy;
use ValueError;

/**
 * Constant backoff strategy
 *
 * This strategy always returns the same value for backing off.  As such it has no mutable state
 *
 * @package gordon\pdowrapper\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
readonly class Constant implements IBackoffStrategy
{
    public function __construct(private int $backoffVal)
    {
        if ($backoffVal < 1) {
            throw new ValueError("Backoff value must be positive");
        }
    }

    /**
     * @inheritDoc
     */
    public function backoff(): int
    {
        return $this->backoffVal;
    }
}
