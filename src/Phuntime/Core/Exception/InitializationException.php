<?php
declare(strict_types=1);

namespace Phuntime\Core\Exception;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class InitializationException extends \Error
{

    /**
     * Thrown when passed something else than FunctionInterface or Closure as a handler.
     * @param mixed $type
     * @return InitializationException
     */
    public static function invalidFunctionPassed($type)
    {
        return new self(
            sprintf(
                'Invalid function passed! Function passed to Phuntime must be Closure or FunctionInterface, %s passed',
                gettype($type)
            )
        );
    }
}