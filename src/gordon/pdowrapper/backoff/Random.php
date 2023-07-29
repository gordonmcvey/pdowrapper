<?php

declare(strict_types=1);

namespace gordon\pdowrapper\backoff;

use gordon\pdowrapper\interface\backoff\IBackoffStrategy;
use Random\Randomizer;
use ValueError;

/**
 * Randomised backoff strategy
 *
 * This strategy will return a random figure between the given min and max values.  As such it has no mutable state
 *
 * @package gordon\pdowrapper\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class Random implements IBackoffStrategy
{
    /**
     * @param int $clampMin Smallest value that can be returned, in microseconds
     * @param int $clampMax Largest value that can be returned, in microseconds
     * @param Randomizer|null $randomiser Allows you to set a pre-configured Randomizer.  If not specified a default Randomizer will be lazy-instantiated on first call to backoff()
     */
    public function __construct(
        private readonly int $clampMin = 10000,
        private readonly int $clampMax = 1000000,
        private ?Randomizer  $randomiser = null
    )
    {
        if ($clampMin < 1) {
            throw new ValueError("Minimum clamp value must be positive");
        }
        if ($clampMax < 1) {
            throw new ValueError("Maximum clamp value must be positive");
        }
        if ($clampMin > $clampMax) {
            throw new ValueError("Minimum clamp value must be greater than maximum clamp value");
        }
    }

    /**
     * @inheritDoc
     */
    public function backoff(): int
    {
        return $this->getRandomiser()->getInt($this->clampMin, $this->clampMax);
    }

    /**
     * Get a Randomizer instance
     *
     * Protected to allow overriding in unit tests etc
     *
     * @return Randomizer
     */
    private function getRandomiser(): Randomizer
    {
        if (null === $this->randomiser) {
            $this->randomiser = new Randomizer();
        }
        return $this->randomiser;
    }
}
