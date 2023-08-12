<?php

declare(strict_types=1);

namespace gordon\pdowrapper\transaction;

use gordon\pdowrapper\interface\transaction\IVerb;
use gordon\pdowrapper\PDOStatement;
use ValueError;

class VerbFactory
{
    private const TYPE_MAP = [
        "SELECT" => Select::class,
        "INSERT" => Insert::class,
        "UPDATE" => Update::class,
        "DELETE" => Delete::class,
    ];

    private const VERB_MATCH = "/^\s*(SELECT|INSERT|UPDATE|DELETE)/i";

    /**
     * @param PDOStatement $statement
     * @return IVerb
     */
    public function get(PDOStatement $statement): IVerb
    {
        $matches = [];
        $match = preg_match(self::VERB_MATCH, $statement->query, $matches);
        if (!$match) {
            throw new ValueError("Given query doesn't match a verb that can be used in a transaction");
        }

        $class = self::TYPE_MAP[strtoupper($matches[1])] ?? null;
        if (!$class) {
            throw new ValueError("Verb not recognised");
        }
        $instance =  new $class($statement);

        if (!$instance instanceof IVerb) {
            throw new ValueError("Instantiated class was not a verb");
        }

        return $instance;
    }
}
