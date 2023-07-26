<?php

declare(strict_types=1);

namespace gordon\pdowrapper\backoff;

use gordon\pdowrapper\interface\backoff\IBackoffStrategy;
use Random\Randomizer;

/**
 * Randomised backoff strategy
 *
 * This strategy will return a random figure between the given min and max values.  As such it has no mutable state
 *
 * @package gordon\pdowrapper\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
readonly class Random implements IBackoffStrategy
{
    private ?Randomizer $randomiser;

    public function __construct(
        private int $clampMin = 10000,
        private int $clampMax = 1000000
    ) {
        if ($clampMin < 1) {
            throw new \InvalidArgumentException("Minimum clamp value must be positive");
        }
        if ($clampMax < 1) {
            throw new \InvalidArgumentException("Maximum clamp value must be positive");
        }
        if ($clampMin > $clampMax) {
            throw new \InvalidArgumentException("Minimum clamp value must be greater than maximum clamp value");
        }
    }

    /**
     * @inheritDoc
     */
    public function backoff(): int
    {
        if (null === $this->randomiser) {
            $this->randomiser = new Randomizer();
        }
        return $this->randomiser->getInt($this->clampMin, $this->clampMax);
    }
}
