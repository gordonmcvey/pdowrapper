<?php

declare(strict_types=1);

namespace gordon\pdowrapper\backoff;

use gordon\pdowrapper\interface\backoff\IStatefulBackoffStrategy;
use InvalidArgumentException;

/**
 * Linear backoff strategy
 *
 * This strategy adds a specific constant value to the previously returned backoff value every time its called up to the
 * clamp value, resulting in a backoff value that grows in a linear fashion
 *
 * @package gordon\pdowrapper\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class Linear implements IStatefulBackoffStrategy
{
    private int $currentBackoff;

    public function __construct(
        private readonly int $initialBackoff = 1000,
        private readonly int $factor = 1000,
        private readonly int $clamp = PHP_INT_MAX
    ) {
        if ($initialBackoff < 1) {
            throw new InvalidArgumentException("Initial backoff value must be positive");
        }
        if ($clamp < 1) {
            throw new InvalidArgumentException("Clamp value must be positive");
        }
        if ($factor < 1) {
            throw new InvalidArgumentException("Backoff factor must be positive");
        }
        if ($initialBackoff > $clamp) {
            throw new InvalidArgumentException("Clamp value must be higher than the initial backoff value");
        }
        $this->currentBackoff = $this->initialBackoff;
    }

    /**
     * @inheritDoc
     */
    public function backoff(): int
    {
        $backoffToReturn = $this->currentBackoff;
        $this->currentBackoff = (int) min($backoffToReturn + $this->factor, $this->clamp);
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
