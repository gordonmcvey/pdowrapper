<?php

declare(strict_types=1);

namespace gordon\pdowrapper\interface\transaction;

interface IVerb
{
    public function exec(): static;
}
