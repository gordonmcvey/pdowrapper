<?php

declare(strict_types=1);

namespace gordon\pdowrapper\exception;

use gordon\pdowrapper\interface\errorcode\IErrorCodeEnum;
use PDOException as BasePDOException;
use Throwable;

/**
 *
 * @package gordon\pdowrapper\exception
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
class PDOException extends BasePDOException
{
    /**
     * @inheritDoc
     * @param IErrorCodeEnum|null $errorCodeEnum An instance of the ErrorCodeEnum that represents the detected error
     */
    public function __construct(
        string                          $message = "",
        int|string                      $code = 0,
        ?Throwable                      $previous = null,
        public readonly ?IErrorCodeEnum $errorCodeEnum = null
    ) {
        parent::__construct(message: $message, previous: $previous);
        /*
         * PHP typing fun: The in-built PDOException is instantiated by PDO and PDOStatement (you shouldn't see "throw
         * new PDOException" in regular PHP code).  For this exception class, $code is set to the SQLSTATE value, which
         * is a string type.  However, PDOException extends RuntimeException which inherits from the base Exception,
         * which define $code as an int.  Setting a string $code shouldn't be allowed, but PHP can break its own typing
         * rules for built-in exceptions
         *
         * We, however, are obliged to follow the language-defined typing rules.  If we try to pass the original
         * exception's $code in as the $code argument on the constructor, the whole thing blows up with a fatal error,
         * even if we redefine $code as int|string in the constructor arguments.
         *
         * But, in their infinite wisdom, the PHP group decided to allow for an exception's state to be mutable!  Why
         * is this allowed?  No idea, an exception should be immutable once created as we could mess with it in ways we
         * shouldn't.  But in this case we can use it to work around the PHP typing rules and have our custom
         * PDOException behave just like the in-built PDOException.
         */
        $this->code = $code;
    }

    /**
     * Factory method for building an exception instance from a generic PDOException
     *
     * @param BasePDOException $e
     * @return PDOException
     * @todo Populate $errorCodeEnum
     */
    public static function fromException(BasePDOException $e): PDOException
    {
        // If the passed exception is already a PDOWrapper exception then just rethrow to avoid a possible infinite loop
        if ($e instanceof self) {
            return $e;
        }

        $newE = new self(message: $e->getMessage(), code: $e->getCode(), previous: $e);
        // It's weird that PHP lets us do this, but it does at least mean we can make sure our subclass behaves exactly
        // like the superclass when used as such
        $newE->errorInfo = $e->errorInfo;
        return $newE;
    }
}
