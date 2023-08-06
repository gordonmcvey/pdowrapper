<?php

declare(strict_types=1);

namespace gordon\pdowrapper\interface\factory;

use gordon\pdowrapper\exception\InstantiationException;
use PDO;
use PDOException;
use TypeError;

/**
 * Interface ConnectionFactoryInterface
 *
 * Implementing class responsibilities: Instantiate the backend PDO object
 *
 * NOTE: This interface's responsibilities should be limited to instantiating a new PDO object and nothing else.
 * Implementing classes can implement additional logic to assist with that task (such as automatic retry if initial
 * connection attempt fails), but things not directly related to instantiating a PDO (such as caching the connection,
 * configuring the new connection via setAttribute(), etc) should be left up to client classes
 *
 * @package gordon\pdowrapper\interface\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface IConnectionFactory
{
    /**
     * Get a new PDO connection
     *
     * @return PDO
     * @throws PDOException
     * @throws InstantiationException
     * @throws TypeError
     */
    public function get(): PDO;
}
