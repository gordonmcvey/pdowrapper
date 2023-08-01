<?php

declare(strict_types=1);

namespace gordon\pdowrapper\backoff;

use gordon\pdowrapper\interface\backoff\IStatefulBackoffStrategy;
use ValueError;

/**
 * Exponential backoff strategy
 *
 * This strategy multiplies the previously returned value by a constant amount, resulting in a backoff that grows
 * in an exponential manner, up to the clamp value.
 *
 * @package gordon\pdowrapper\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class Exponential implements IStatefulBackoffStrategy
{
    private int $currentBackoff;

    public function __construct(
        private readonly int   $initialBackoff = 1000,
        private readonly float $factor = 1.1,
        private readonly int   $clamp = PHP_INT_MAX
    ) {
        if ($initialBackoff < 1) {
            throw new ValueError("Initial backoff value must be positive");
        }
        if ($clamp < 1) {
            throw new ValueError("Clamp value must be positive");
        }
        if ($factor <= 1) {
            throw new ValueError("Backoff factor must be greater than 1");
        }
        if ($initialBackoff > $clamp) {
            throw new ValueError("Clamp value must be higher than the initial backoff value");
        }
        $this->currentBackoff = $this->initialBackoff;
    }

    /**
     * @inheritDoc
     */
    public function backoff(): int
    {
        $backoffToReturn = $this->currentBackoff;
        $this->currentBackoff = (int) min($backoffToReturn * $this->factor, $this->clamp);
        return $backoffToReturn;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        $this->currentBackoff = $this->initialBackoff;
        return $this;
    }
}
