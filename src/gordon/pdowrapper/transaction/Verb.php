<?php

declare(strict_types=1);

namespace gordon\pdowrapper\transaction;

use gordon\pdowrapper\interface\transaction\IVerb;
use gordon\pdowrapper\PDOStatement;

/**
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
abstract class Verb implements IVerb
{
    public function __construct(public readonly PDOStatement $statement)
    {
    }

    public function exec(): static
    {
        $this->statement->execute();
        return $this;
    }
}
