<?php

declare(strict_types=1);

namespace gordon\pdowrapper\interface\backoff;

/**
 *
 * @package gordon\pdowrapper\interface\backoff
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface IStatefulBackoffStrategy extends IBackoffStrategy
{
    /**
     * Reset the backoff strategy to its initial state
     *
     * @return $this
     */
    public function reset(): static;
}
