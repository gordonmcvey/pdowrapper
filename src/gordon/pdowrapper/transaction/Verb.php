<?php

declare(strict_types=1);

namespace gordon\pdowrapper\transaction;

use gordon\pdowrapper\interface\transaction\IVerb;
use gordon\pdowrapper\PDOStatement;

abstract class Verb implements IVerb
{
    public function __construct(public readonly PDOStatement $statement)
    {
    }
}
