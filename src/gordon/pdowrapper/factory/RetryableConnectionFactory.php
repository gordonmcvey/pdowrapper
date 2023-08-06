<?php

declare(strict_types=1);

namespace gordon\pdowrapper\factory;

use gordon\pdowrapper\connection\ConnectionSpec;
use gordon\pdowrapper\exception\ConnectionAttemptsExceededException;
use gordon\pdowrapper\interface\backoff\IBackoffStrategy;
use PDO;
use PDOException;

/**
 *
 * @package gordon\pdowrapper\factory
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class RetryableConnectionFactory extends ConnectionFactory
{
    public function __construct(
        ConnectionSpec                    $spec,
        private readonly IBackoffStrategy $backoffStrategy,
        private readonly int              $maxAttempts = 5
    ) {
        parent::__construct($spec);
    }

    public function get(): PDO
    {
        $attempt = 1;

        while (true) {
            $this->logger?->debug(sprintf("%s: Connection attempt %d of %d", __METHOD__, $attempt, $this->maxAttempts));
            try {
                // The return will escape the loop
                $connection = parent::get();
                $this->logger?->debug(sprintf("%s: Connection established after %d attempt(s)", __METHOD__, $attempt));
                return $connection;
            } catch (PDOException $ex) {
                // If the error in the exception indicates a temporary failure (such as "no route to host", "connection
                // timeout", etc.) then trigger the retry logic.  Otherwise, abandon any further connection attempts
                if (!$this->isRecoverable($ex)) {
                    throw $ex;
                }

                // Increment attempt counter and bail out if retry limit exceeded
                if (++$attempt > $this->maxAttempts) {
                    throw new ConnectionAttemptsExceededException(
                        sprintf("Failed to establish connection after %d attempt(s)", $attempt),
                        $ex->getCode(),
                        $ex
                    );
                }

                // Sleep for a number of microseconds specified by the backoff strategy before making another connection
                // attempt
                $backoff = $this->backoffStrategy->backoff();
                $this->logger?->debug(sprintf("Retrying in %0.5d second(s)", $backoff / 1000000));
                usleep($backoff);
            }
        }
    }

    /**
     * Determine whether the given PDO error indicates that the connection attempt can be retried
     *
     * This method determines whether the given PDOException indicates a failure mode that could be recovered from by
     * re-trying the connection (such as a network error), or is an error that cannot be recovered from without
     * intervention (like incorrect login credentials, etc)
     *
     * @param PDOException $ex
     * @return bool
     */
    private function isRecoverable(PDOException $ex): bool
    {
        // @todo Put logic to distinguish recoverable from fatal exceptions here
        return true;
    }
}
