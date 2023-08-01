<?php

namespace gordon\pdowrapper\interface\errorcode;

/**
 * CodeClassifierInterface interface
 *
 * This is an interface for classes that attempt to classify a given SQL result codes based on what response is
 * reasonable to make for them.  A given code could potentially be ignored, be indicative of a temporary error that may
 * be recovered from if the last query (or transaction) was replayed, or indicative of a temporary error that may be
 * recovered from by re-connecting to the database server and replaying the last query (or transaction)
 *
 * @package gordon\pdowrapper\interface\errorcode
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
interface ICodeClassifier
{
    /**
     * Determine whether the given code can be ignored and that we can continue safely
     *
     * @param IErrorCodeEnum $code
     * @return bool
     */
    public function canIgnore(IErrorCodeEnum $code): bool;

    /**
     * Determine whether the given code represents an execution error that may be temporary and can therefore be
     * recovered from by replaying the last query/transaction
     *
     * @param IErrorCodeEnum $code
     * @return bool
     */
    public function canReplay(IErrorCodeEnum $code): bool;

    /**
     * Determine whether the given code represents a network/server error leading to a temporary loss of connectivity
     * that may be recovered from by attempting to reconnect and replaying the last query/transaction
     *
     * Generally speaking, reconnectable error codes are a subset of replayable error codes, as it may be possible to
     * replay the last query/transaction after recovery, though an additional step of reconnecting to the database
     * server has to be successfully accomplished first, and it may also be necessary to re-prepare any prepared
     * statements that were in use at the time the connection was lost.
     *
     * @param IErrorCodeEnum $code
     * @return bool
     */
    public function canReconnect(IErrorCodeEnum $code): bool;
}
